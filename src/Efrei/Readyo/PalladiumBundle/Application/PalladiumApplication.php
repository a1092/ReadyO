<?php

namespace Efrei\Readyo\PalladiumBundle\Application;

use Doctrine\ORM\EntityManager;

use Efrei\Readyo\PalladiumBundle\Exception\AuthenticationException;
use Efrei\Readyo\PalladiumBundle\Exception\TopicException;
use Efrei\Readyo\PalladiumBundle\Exception\IncomingException;
use Efrei\Readyo\PalladiumBundle\Exception\ApplicationException;

use Efrei\Readyo\PalladiumBundle\Entity\Application;
use Efrei\Readyo\PalladiumBundle\Entity\Topic;
use Efrei\Readyo\PalladiumBundle\Entity\Message;

class PalladiumApplication extends \Varspool\WebsocketBundle\Application\Application
{

	private $applications;
    private $topics;
	protected $logger;

    private $_TOPICS = array(
        "register" => "fr/readyo/palladium/register",
        "connected" => "fr/readyo/palladium/system/connected",
        "keepalive" => "fr/readyo/palladium/system/keepalive"
    );

	public function __construct(EntityManager $em) {
    	$this->em = $em;

        $this->logger = function($message, $level = 'info') {
            echo "[".strtoupper($level)."]\t[".date("d/m/Y H:i:s")."]\t".$message."\n";
        };


        $this->setLogger(function($message, $level = 'info') {
            echo "[".strtoupper($level)."]\t[".date("d/m/Y H:i:s")."]\t".$message."\n";
        });


        $this->applications = array();
        $this->topics = array();

        $this->loadTopics();
    }

    public function getName() {
        return 'palladium';
    }

    public function onConnect($client) {

    }


	public function onDisconnect($client) {

        if(array_key_exists($client->getId(), $this->applications)) {
            
            $application = $this->applications[$client->getId()];

            $this->log("The application ".$application->getName()." was disconnected.");

            foreach($application->getTopics() as $topic) {
                $this->topics[$topic->getPath()]->removeApplication($application);
            }

            unset($this->applications[$client->getId()]);
        }
    }

    public function onData($incoming, $client) {
        
        $messages = array();

        $message = new Message();

        try {
            try {

                // Validate and convert incoming data
                $incoming = $this->schemaValidation($incoming);

                // Retrieve the topic
                $message->setTopic($this->retrieveTopic($incoming["topic"]));

                // Retrieve the content
                $message->setData(json_encode($incoming["data"]));

                // Récupération de l'application qui envoie un message
                $message->setApplication($this->retrieveApplication($client->getId()));

                $this->broadcastSubscribers($message);
                
                $messages[] = $message;

                $this->log("[".$message->getTopic()."]\t".json_encode($message->getData()), "INFO");

            } catch(AuthenticationException $e) {
                
                // Si ce n'est pas un message d'Authentification, on renvoie une erreur
                if($message->getTopic()->getPath() != $this->_TOPICS["register"]) throw $e;

                // On récupère l'application
                $application = $this->registerApplication($message->getData()->privateKey, $message->getData()->subscribtions);

                // On renseigne le client
                $application->setClient($client);

                $message->setApplication($application);

                $this->applications[$client->getId()] = $application;

                $this->log("Authentication succeeded for ".$application->getName());
                
                $messages[] = $message;


                $authMessage = new Message();
                $authMessage->setApplication($application);
                $authMessage->setTopic($this->topics[$this->_TOPICS["connected"]]);
                $this->broadcastSubscribers($authMessage);
                $messages[] = $authMessage;
            }

            foreach($messages as $message) {
                
                if($message->getTopic()->getLog() == true)
                    $this->em->persist($message);
            }

            $this->em->flush();

        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }
    }


    private function broadcastSubscribers(\Efrei\Readyo\PalladiumBundle\Entity\Message $message) {
        
        if(count($message->getTopic()->getApplications())) {
            foreach($message->getTopic()->getApplications() as $application) {
                //if($application->getId() != $message->getApplication()->getId())
                    $application->getClient()->send(json_encode(array(
                        "time" => $message->getReceivedAt()->getTimestamp(),
                        "from" => (empty($message->getApplication()) ? 'SYSTEM' : $message->getApplication()->getName()),
                        "topic" => $message->getTopic()->getPath(),
                        "data" => $message->getData()
                    )));
            }
        }
    }


    private function schemaValidation($incoming) {
        $data = json_decode($incoming, true);

        if(json_last_error() != JSON_ERROR_NONE)
            throw new IncomingException($incoming);

        if(!array_key_exists("topic", $data))
            throw new IncomingException($incoming);

        if(!array_key_exists("data", $data))
            throw new IncomingException($incoming);

        return $data;
    }

    private function retrieveTopic($path) {

        if(!array_key_exists($path, $this->topics)) {
        
            $topic = $this->em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneByPath($path); 

            if(!$topic)
                throw new TopicException($path);

            $this->topics[$topic->getPath()] = $topic;
        }

        return $this->topics[$path];
    }

    private function registerApplication($privateKey, $matchers) {
        
        $application = $this->em->getRepository('EfreiReadyoPalladiumBundle:Application')->findOneBy(array(
            "privateKey" => $privateKey,
            "active" => true
        )); 

        if(!$application)
            throw new ApplicationException("Bad privateKey.");

        // On vérifie que l'application n'est pas déjà en cours d'utilisation
        foreach($this->applications as $appConnected) {
            if($appConnected->getId() == $application->getId())
                throw new ApplicationException("Application already connected.");               
        }

        if($matchers)
            foreach($matchers as $matcher) {
                        
                $application->addTopicMatcher($matcher);

                foreach($this->topics as $topic) {
                    
                    if(preg_match("#".$matcher."#", $topic->getPath())) {
                        
                        $application->addTopic($topic);
                        $this->topics[$topic->getPath()]->addApplication($application);

                        $this->log("Application ".$application->getName()." has subscribed to topic '".$topic->getPath()."'", "INFO");
                    }
                }
            }

        return $application;
    }


    private function retrieveApplication($clientid) {

        if(!array_key_exists($clientid, $this->applications))
            throw new AuthenticationException();

        return $this->applications[$clientid];
    }


    private function loadTopics() {
        
        $this->topics = array();

        foreach($this->em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findAll() as $topic) {
            $this->topics[$topic->getPath()] = $topic;
        }
    }
}