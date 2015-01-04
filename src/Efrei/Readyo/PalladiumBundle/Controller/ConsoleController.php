<?php

namespace Efrei\Readyo\PalladiumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ConsoleController extends Controller
{
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        return $this->render('EfreiReadyoPalladiumBundle:Console:console.html.twig', array(
        	
            "topics" => $em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findBy(array(), array("path" => "ASC")),
            "palladium" => array(
                "host" => $this->container->getParameter('palladium_host'),
                "port" => $this->container->getParameter('palladium_port'),
                "channel" => $this->container->getParameter('palladium_channel'),
                "key" => $this->container->getParameter('palladium_console_key'),
            )
        ));
    }

}
