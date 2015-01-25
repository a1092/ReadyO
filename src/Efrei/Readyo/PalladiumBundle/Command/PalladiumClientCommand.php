<?php

namespace Efrei\Readyo\PalladiumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use JMS\Serializer\SerializationContext;

use Wrench\Client;

class PalladiumClientCommand extends ContainerAwareCommand
{

	private $_em = null;
    private $_client = null;
    private $_serializer = null;

    private $time2reconnect = 0;
    private $starting = true;

	protected function configure() {
        
        $this
            ->setName('palladium:client')
            ->setDescription('Client Backend pour le Bus Palladium.')
            ->addOption('key', null, InputOption::VALUE_NONE, 'The privateKey of the application.')
            ->addOption('refresh', null, InputOption::VALUE_NONE, 'Refresh time beetween.')
            ->addOption('host', null, InputOption::VALUE_NONE, 'Address of Palladium Server.')
            ->addOption('port', null, InputOption::VALUE_NONE, 'Port of Palladium Server.')
            ->addOption('channel', null, InputOption::VALUE_NONE, 'Channel of Palladium Server.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {        
        
        $this->_em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->_serializer = $this->getContainer()->get('jms_serializer');






        $host = $this->getContainer()->getParameter('palladium_host');
        $port = $this->getContainer()->getParameter('palladium_port');
        $channel = $this->getContainer()->getParameter('palladium_channel');

        $key = $this->getContainer()->getParameter('palladium_client_key');

       // $key = $input->getOption('key');
        
        do {

            try {
                $this->palladiumConnection($host, $port, $channel, $key);

            	while($this->_client->isConnected()) {

                    $this->time2reconnect = 0;
 
                    $this->_client->receive();

                    $this->checkSchedule();

                    sleep(60);
                }
            } catch(\Exception $e) {
                echo "[ERROR] ".$e->getMessage()."\n";

                if($this->time2reconnect < 120)
                    $this->time2reconnect += 10;

                echo "[INFO] Trying new connection in ".$this->time2reconnect." sec. \n";
            }


            sleep($this->time2reconnect);

        } while(true);

    }

    private function palladiumConnection($host, $port, $channel, $key) {

        $this->_client = new Client("ws://".$host.":".$port."/".$channel, 'http://localhost/');
        $this->_client->connect();

        $this->_client->sendData(json_encode(array(
            "topic" => "fr/readyo/palladium/register",
            "data" => array(
                "privateKey" => $key, 
                "subscribtions" => array()
            )
        )));

        if($this->_client->isConnected())
            echo "[INFO] Connection to Palladium Server successful. \n";
    }

    private function checkSchedule() {

        // Récupération des programmes en cours
        $schedules = $this->_em->getRepository('EfreiReadyoWebradioBundle:Schedule')->inDiffusion(1, 1);

        if(count($schedules) > 0) {

            echo "[INFO] ".count($schedules)." programme(s) trouvé(s). \n";

            if($this->starting || $schedules[0]->getDiffusedAt()->format('Y-m-d H:i') == (new \Datetime())->format('Y-m-d H:i')) {
            
                $this->_client->sendData(json_encode(array(
                    "topic" => "fr/readyo/palladium/webradio/schedule/begining",
                    "data" => json_decode($this->_serializer->serialize($schedules[0], 'json', SerializationContext::create()->setGroups(array('details'))))
                    
                )));

                if(!empty($schedules[0]->getSpotifyUri())) {
                    $this->_client->sendData(json_encode(array(
                        "topic" => "fr/readyo/palladium/music/play",
                        "data" => array(
                            "spotify" => $schedules[0]->getSpotifyUri()
                        )
                    )));
                }

                if(count($schedules[0]->getOutputs()) > 0) {
                    foreach($schedules[0]->getOutputs() as $output) {

                        $this->_client->sendData(json_encode(array(
                            "topic" => "fr/readyo/palladium/output/open",
                            "data" => array(
                                "channel" => $output->getId(),
                                "name" => $output->getName()
                            )
                        )));

                    }
                }

            } else if($this->starting || $schedules[0]->getFinishedAt()->format('Y-m-d H:i') == (new \Datetime())->format('Y-m-d H:i')) {

                $this->_client->sendData(json_encode(array(
                    "topic" => "fr/readyo/palladium/webradio/schedule/ending",
                    "data" => json_decode($this->_serializer->serialize($schedules[0], 'json', SerializationContext::create()->setGroups(array('details'))))
                )));


                if(!empty($schedules[0]->getSpotifyUri())) {
                    $this->_client->sendData(json_encode(array(
                        "topic" => "fr/readyo/palladium/music/stop",
                        "data" => array()
                    )));
                }

                if(count($schedules[0]->getOutputs()) > 0) {
                    foreach($schedules[0]->getOutputs() as $output) {
                        $this->_client->sendData(json_encode(array(
                            "topic" => "fr/readyo/palladium/output/close",
                            "data" => array(
                                "channel" => $output->getId(),
                                "name" => $output->getName()
                            )
                        )));
                    }
                }
            }


            //On enlève les schedule du cache de l'Entity Manager
            foreach($schedules as $schedule)
                $this->_em->detach($schedule);

        } else {
            echo "[INFO] Aucun programme de prévu. \n";
        }



        
    }

    
}