<?php

namespace Efrei\Readyo\WebradioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Efrei\Readyo\WebradioBundle\Entity\Show;
use Efrei\Readyo\WebradioBundle\Form\ShowForm;


class ShowController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shows = $em->getRepository('EfreiReadyoWebradioBundle:Show')->findAll();

        return $this->render('EfreiReadyoWebradioBundle:Show:index.html.twig', array(
        	'shows' => $shows
        ));
    }

    public function newAction(Request $request) {

        $show = new Show();

        $form = $this->createForm(new ShowForm(), $show, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_show_show', array('showid' => $show->getId())));
        }

        return $this->render('EfreiReadyoWebradioBundle:Show:new.html.twig', array(
            'form' => $form->createView(),
            'show' => $show
        ));
    }


    public function editAction(Request $request, $showid) {

        $em = $this->getDoctrine()->getManager();

        $show = $em->getRepository('EfreiReadyoWebradioBundle:Show')->findOneById($showid);

        if (!$show) {
            throw $this->createNotFoundException('Unable to find Show.');
        }

        $form = $this->createForm(new ShowForm(), $show, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) { 

            $em->persist($show);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_show_show', array('showid' => $show->getId())));
        }

        return $this->render('EfreiReadyoWebradioBundle:Show:new.html.twig', array(
            'form' => $form->createView(),
            'show' => $show
        ));
    }

    public function showAction($showid)
    {
        $em = $this->getDoctrine()->getManager();

        $show = $em->getRepository('EfreiReadyoWebradioBundle:Show')->findOneById($showid);

        return $this->render('EfreiReadyoWebradioBundle:Show:show.html.twig', array(
            'show' => $show
        ));
    }
}
