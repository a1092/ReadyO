<?php

namespace Efrei\Readyo\PalladiumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Efrei\Readyo\PalladiumBundle\Entity\Topic;
use Efrei\Readyo\PalladiumBundle\Form\TopicType;

class TopicController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $topics = $em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findAll();

        return $this->render('EfreiReadyoPalladiumBundle:Topic:index.html.twig', array(
        	"topics" => $topics
        ));
    }

    public function showAction($topicid)
    {	
    	$em = $this->getDoctrine()->getManager();

        $topic = $em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneById($topicid);

        return $this->render('EfreiReadyoPalladiumBundle:Topic:index.html.twig', array(
        	"topic" => $topic
        ));
    }

    public function newAction(Request $request)
    {
    	$topic = new Topic();

        $form = $this->createForm(new TopicType(), $topic, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_palladium_topic', array()));
        }

        return $this->render('EfreiReadyoPalladiumBundle:Topic:edit.html.twig', array(
            'form' => $form->createView(),
            'topic' => $topic
        ));
    }


    public function editAction(Request $request, $topicid)
    {
        $em = $this->getDoctrine()->getManager();

        $topic = $em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneById($topicid);

        if (!$topic) {
            throw $this->createNotFoundException('Unable to find Topic.');
        }

        $form = $this->createForm(new TopicType(), $topic, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_palladium_topic', array()));
        }

        return $this->render('EfreiReadyoPalladiumBundle:Topic:edit.html.twig', array(
            'form' => $form->createView(),
            'topic' => $topic
        ));
    }
}
