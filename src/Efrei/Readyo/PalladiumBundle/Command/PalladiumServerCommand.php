<?php

namespace Efrei\Readyo\PalladiumBundle\Command;

use Varspool\WebsocketBundle\VarspoolWebsocketBundle;

use \InvalidArgumentException;

use Wrench\Server;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class PalladiumServerCommand extends ContainerAwareCommand
{
    protected $levels = array(
        "DEBUG" => 0,
        "INFO" => 1,
        "WARNING" => 2,
        "ERROR" => 3
    );

    private $default_level = "INFO";

    /**
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('palladium:server')
            ->setDescription('Serveur & Backbone du Bus Palladium')
            ->addArgument(
                'level',
                InputArgument::OPTIONAL,
                '',
                $this->default_level
            )
        ;
    }

    /**
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $levels = $this->levels;
        $level_limit = strtoupper($input->getArgument('level'));

        if(!array_key_exists($level_limit, $levels)) {
            $level_limit = $this->default_level;
            $output->writeln("Bad level. Default level used : ".$level_limit);
        }

        $manager = $this->getContainer()->get('varspool_websocket.server_manager');
        

        $manager->setLogger(function ($message, $level) use ($output, $levels, $level_limit) {
            $level = strtoupper($level);
            if($levels[$level] >= $levels[$level_limit])
                $output->writeln("[".$level."]\t[".date("d/m/Y H:i:s")."]\t".$message);
        });

        $server = $manager->getServer("default");
    	$server->run();
    }
}