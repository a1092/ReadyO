<?php

namespace Efrei\Readyo\PalladiumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Efrei\Readyo\PalladiumBundle\Entity\Output;
use Efrei\Readyo\PalladiumBundle\Form\OutputType;

class OutputController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $outputs = $em->getRepository('EfreiReadyoPalladiumBundle:Output')->findAll();

        return $this->render('EfreiReadyoPalladiumBundle:Output:index.html.twig', array(
        	"outputs" => $outputs
        ));
    }

    public function showAction($outputid)
    {	
    	$em = $this->getDoctrine()->getManager();

        $output = $em->getRepository('EfreiReadyoPalladiumBundle:Output')->findOneById($outputid);

        return $this->render('EfreiReadyoPalladiumBundle:Output:index.html.twig', array(
        	"output" => $output
        ));
    }

    public function newAction(Request $request)
    {
    	$output = new Output();

        $form = $this->createForm(new OutputType(), $output, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($output);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_palladium_output', array()));
        }

        return $this->render('EfreiReadyoPalladiumBundle:Output:edit.html.twig', array(
            'form' => $form->createView(),
            'output' => $output
        ));
    }


    public function editAction(Request $request, $outputid)
    {
        $em = $this->getDoctrine()->getManager();

        $output = $em->getRepository('EfreiReadyoPalladiumBundle:Output')->findOneById($outputid);

        if (!$output) {
            throw $this->createNotFoundException('Unable to find Output.');
        }

        $form = $this->createForm(new OutputType(), $output, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($output);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_palladium_output', array()));
        }

        return $this->render('EfreiReadyoPalladiumBundle:Output:edit.html.twig', array(
            'form' => $form->createView(),
            'output' => $output
        ));
    }
}
