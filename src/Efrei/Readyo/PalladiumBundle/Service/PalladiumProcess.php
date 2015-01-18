<?php 

namespace Efrei\Readyo\PalladiumBundle\Service;

use Doctrine\ORM\EntityManager;
use Efrei\Readyo\PalladiumBundle\Entity\Message;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\HeaderBag;

class PalladiumProcess
{
	private $_em;
	private $_jwtManager;

	public function __construct(EntityManager $em, JWTManager $jwtManager) {
		$this->_em = $em;
		$this->_jwtManager = $jwtManager;

	}

	public function process(Message $m)	{
		$messages = array();

		switch($m->getTopic()) {

			case "fr/readyo/palladium/live/authenticate/check":
				
				$user = $this->checkCredentials($m->getData()->token, $m->getData()->userAgent, $m->getData()->ip);

				if($user != null)
					$messages[] = $this->generateMessage("fr/readyo/palladium/live/authenticate/success", array(
						"userid" => $user->getId(),
						"username" => $user->getUsername(),
						"firstname" => $user->getFirstname(),
						"lastname" => $user->getLastname(),
						"picture" => $user->picture(),
					), $m->getReference());
				else
					$messages[] = $this->generateMessage("fr/readyo/palladium/live/authenticate/fail", array(), $m->getReference());

				break;
		}

		return $messages;
	}

	private function checkCredentials($token, $useragent, $ip) {
		

		$request = new Request();

		//$query, $request, $attributes, $cookies, $files, $server, $content
		//$request->initialize(array(), array(), array(), array(), array(), , "");

		$request->request = new ParameterBag(array());
        $request->query = new ParameterBag(array());
        $request->server = new ServerBag(array("REMOTE_ADDR" => $ip));
        $request->headers = new HeaderBag(array("User-Agent" => $useragent, "Authorization" => "Token ".$token));

		$this->_jwtManager->setRequest($request);

		$t = new JWTUserToken();
    	$t->setRawToken($token);

    	$payload = $this->_jwtManager->decode($t);

    	// No token available
    	if(!$payload)
	    	return null;

	    return $this->_em->getRepository('EfreiReadyoUserBundle:User')->findOneByUsername($payload["username"]);
	}

	private function generateMessage($topic, $data, $reference=null) {
		
		$message =  new Message();
		$message->setTopic($this->retrieveTopic($topic));
		$message->setData(json_encode($data));
		$message->setReference($reference);

		return $message;
	}

	public function retrieveTopic($path) {

		$topic = $this->_em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneByPath($path); 

            if(!$topic)
                throw new TopicException($path);
        return $topic;
/*
        if(!array_key_exists($path, $this->topics)) {
        
            $topic = $this->_em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneByPath($path); 

            if(!$topic)
                throw new TopicException($path);

            $this->topics[$topic->getPath()] = $topic;
        }

        return $this->topics[$path];
*/
    }
}

