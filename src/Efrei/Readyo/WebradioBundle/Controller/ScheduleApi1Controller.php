<?php

namespace Efrei\Readyo\WebradioBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

use JMS\Serializer\SerializationContext;

class ScheduleApi1Controller extends FOSRestController
{

    private $version = "1.0";

    /**
	 * @ApiDoc(
	 *   description = "Liste toutes les programmes.",
     *   resource = true,
     *   section = "Programmes",
	 *   statusCodes = {
	 *     200 = "Liste des programmes.",
	 *   }
	 * )
	 *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     * @Rest\QueryParam(name="begin", requirements="(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})[+-](\d{2})\:(\d{2})", nullable=true, description="L'émission débute après cette date. Date au format ISO-8601.")
     * @Rest\QueryParam(name="end", requirements="(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})[+-](\d{2})\:(\d{2})", nullable=true, description="L'émission débute avant cette date. Date au format ISO-8601.")
     * @Rest\QueryParam(name="finished", requirements="(0|1)", nullable=true, description="Emissions terminées ?")
     * @Rest\QueryParam(name="live", requirements="(0|1)", nullable=true, description="Emissions en direct ?")
     * @Rest\QueryParam(name="order", requirements="(ASC|DESC)", default="ASC", nullable=true, description="Ordre de diffusion des programmes")
     *
     */
    public function listAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

    	   
        $em = $this->getDoctrine()->getManager();

        $schedules = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->filter(
            null,
            $paramFetcher->get("begin"),
            $paramFetcher->get("end"),
            $paramFetcher->get("finished"),
            $paramFetcher->get("live"),
            $paramFetcher->get("limit"), 
            $paramFetcher->get("offset"),
            $paramFetcher->get("order")
        );
        
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('list'))
            ->setVersion($this->version)
        );
        $view->setData($schedules, 200);

        return $this->handleView($view);
    }



    /**
	 * @ApiDoc(
	 *   description = "Retourne le programme.",
     *   resource = false,
     *   section = "Programmes",
	 *   statusCodes = {
	 *     200 = "Return Programme",
	 *     400 = "No schedule was founded."
	 *   }
	 * )
	 *
     */
    public function showAction($scheduleId)
    {
        $em = $this->getDoctrine()->getManager();

    	$schedule = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->findOneBy(
    		array(
    			"id" => $scheduleId,
    			"isPublish" => true
    		)
    	);

    	if(!$schedule)
    		$view = View::create("No Schedule was founded.", 400);
    	else {
    		$view = $this->view();
            $view->setSerializationContext(SerializationContext::create()
                ->setGroups(array('details'))
                ->setVersion($this->version)
                ->enableMaxDepthChecks()
            );
            $view->setData($schedule, 200);
        }

        return $this->handleView($view);
    }


    /**
	 * @ApiDoc(
	 *   description = "Retourne les podcasts du programme",
     *   resource = false,
     *   section = "Programmes",
	 *   statusCodes = {
	 *     200 = "Return Podcasts list",
	 *     400 = "No show was founded."
	 *   }
	 * )
	 *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     *
     */
    public function podcastsAction(ParamFetcher $paramFetcher, $scheduleId)
    {

        $em = $this->getDoctrine()->getManager();

    	$podcasts = $em->getRepository('EfreiReadyoWebradioBundle:Podcast')->findBySchedule(
    		array("id" => $scheduleId),
            array(),
    		$paramFetcher->get("limit"), 
    		$paramFetcher->get("offset")
    	);

        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('list'))
            ->setVersion($this->version)
        );
        $view->setData($podcasts, 200);

		return $this->handleView($view);
    }
}
