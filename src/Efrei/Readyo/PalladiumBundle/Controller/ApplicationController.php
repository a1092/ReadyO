<?php

namespace Efrei\Readyo\PalladiumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Efrei\Readyo\PalladiumBundle\Entity\Application;
use Efrei\Readyo\PalladiumBundle\Form\ApplicationType;

class ApplicationController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $applications = $em->getRepository('EfreiReadyoPalladiumBundle:Application')->findAll();

        return $this->render('EfreiReadyoPalladiumBundle:Application:index.html.twig', array(
        	"applications" => $applications
        ));
    }

    public function showAction($applicationid)
    {	
    	$em = $this->getDoctrine()->getManager();

        $application = $em->getRepository('EfreiReadyoPalladiumBundle:Application')->findOneById($applicationid);

        return $this->render('EfreiReadyoPalladiumBundle:Application:index.html.twig', array(
        	"application" => $application
        ));
    }

    public function newAction(Request $request)
    {
    	$application = new Application();

        $form = $this->createForm(new ApplicationType(), $application, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_palladium_application', array()));
        }

        return $this->render('EfreiReadyoPalladiumBundle:Application:edit.html.twig', array(
            'form' => $form->createView(),
            'application' => $application
        ));
    }


    public function editAction(Request $request, $applicationid)
    {
        $em = $this->getDoctrine()->getManager();

        $application = $em->getRepository('EfreiReadyoPalladiumBundle:Application')->findOneById($applicationid);

        if (!$application) {
            throw $this->createNotFoundException('Unable to find Application.');
        }

        $form = $this->createForm(new ApplicationType(), $application, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_palladium_application', array()));
        }

        return $this->render('EfreiReadyoPalladiumBundle:Application:edit.html.twig', array(
            'form' => $form->createView(),
            'application' => $application
        ));
    }
}
