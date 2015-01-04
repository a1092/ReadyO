<?php

namespace Efrei\Readyo\WebradioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Efrei\Readyo\WebradioBundle\Entity\Schedule;
use Efrei\Readyo\WebradioBundle\Form\ScheduleForm;


class ScheduleController extends Controller
{
    public function newAction(Request $request, $showId) {

        $em = $this->getDoctrine()->getManager();

        $schedule = new Schedule();

        $show = $em->getRepository('EfreiReadyoWebradioBundle:Show')->findOneById($showId);

        if (!$show) {
            throw $this->createNotFoundException('Unable to find Show.');
        }

        $schedule->setShow($show);

        $form = $this->createForm(new ScheduleForm(), $schedule, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($schedule);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_show_show', array('showid' => $schedule->getShow()->getId())));
        }

        return $this->render('EfreiReadyoWebradioBundle:Schedule:new.html.twig', array(
            'form' => $form->createView(),
            'schedule' => $schedule
        ));
    }


    public function editAction(Request $request, $scheduleId) {

        $em = $this->getDoctrine()->getManager();

        $schedule = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->findOneById($scheduleId);

        if (!$schedule) {
            throw $this->createNotFoundException('Unable to find Schedule.');
        }

        $form = $this->createForm(new ScheduleForm(), $schedule, array(
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) { 

            $em->persist($schedule);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_show_show', array('showid' => $schedule->getShow()->getId())));
        }

        return $this->render('EfreiReadyoWebradioBundle:Schedule:new.html.twig', array(
            'form' => $form->createView(),
            'schedule' => $schedule
        ));
    }

    public function showAction($scheduleid)
    {
        $em = $this->getDoctrine()->getManager();

        $schedule = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->findOneById($scheduleid);

        return $this->render('EfreiReadyoWebradioBundle:Schedule:show.html.twig', array(
            'schedule' => $schedule
        ));
    }
}
