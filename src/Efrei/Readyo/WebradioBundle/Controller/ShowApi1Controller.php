<?php

namespace Efrei\Readyo\WebradioBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

use JMS\Serializer\SerializationContext;

class ShowApi1Controller extends FOSRestController
{

    private $version = "1.0";


    /**
	 * @ApiDoc(
	 *   description = "Liste toutes les émissions.",
     *   resource = true,
     *   section = "Emissions",
	 *   statusCodes = {
	 *     200 = "Liste des émissions.",
	 *   }
	 * )
	 *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     *
     *
     */
    public function listAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

    	$shows = $em->getRepository('EfreiReadyoWebradioBundle:Show')->findBy(
    		array("isPublish" => true),
    		array(),
    		$paramFetcher->get("limit"), 
    		$paramFetcher->get("offset")
    	);

        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('list'))
            ->setVersion($this->version)
            ->setSerializeNull(true)
        );
        $view->setData($shows, 200);

        return $this->handleView($view);
    }



    /**
	 * @ApiDoc(
	 *   description = "Retourne l'émission.",
     *   resource = false,
     *   section = "Emissions",
	 *   statusCodes = {
	 *     200 = "Return Show",
	 *     400 = "No show was founded."
	 *   }
	 * )
	 *
     */
    public function showAction($showId)
    {
        $em = $this->getDoctrine()->getManager();

    	$show = $em->getRepository('EfreiReadyoWebradioBundle:Show')->findOneBy(
    		array(
    			"id" => $showId,
    			"isPublish" => true
    		)
    	);

    	if(!$show) {
    		$view = View::create("No show was founded.", 400);
            return $this->get('fos_rest.view_handler')->handle($view);
        }


    	$view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('details'))
            ->setVersion($this->version)
            ->setSerializeNull(true)
        );
        $view->setData($show, 200);

        return $this->handleView($view);
    }


    /**
	 * @ApiDoc(
	 *   description = "Retourne les programmes de l'émission",
     *   resource = false,
     *   section = "Emissions",
	 *   statusCodes = {
	 *     200 = "Return Schedule list",
	 *     400 = "No show was founded."
	 *   }
	 * )
     *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     * @Rest\QueryParam(name="begin", requirements="(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})[+-](\d{2})\:(\d{2})", nullable=true, description="L'émission débute après cette date. Date au format ISO-8601.")
     * @Rest\QueryParam(name="end", requirements="(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})[+-](\d{2})\:(\d{2})", nullable=true, description="L'émission débute avant cette date. Date au format ISO-8601.")
     * @Rest\QueryParam(name="finished", requirements="(0|1)", nullable=true, description="Emissions terminées ?")
     * @Rest\QueryParam(name="live", requirements="(0|1)", nullable=true, description="Emissions en direct ?")
     *
     */
    public function schedulesAction(ParamFetcher $paramFetcher, $showId)
    {
        $em = $this->getDoctrine()->getManager();

        $schedules = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->filter(
            $showId,
            $paramFetcher->get("begin"),
            $paramFetcher->get("end"),
            $paramFetcher->get("finished"),
            $paramFetcher->get("live"),
            $paramFetcher->get("limit"), 
            $paramFetcher->get("offset")
        );

        
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('list'))
            ->setVersion($this->version)
            ->setSerializeNull(true)
        );
        $view->setData($schedules, 200);

        return $this->handleView($view);
    }


    /**
	 * @ApiDoc(
	 *   description = "Retourne les podcast de l'émission",
     *   resource = false,
     *   section = "Emissions",
	 *   statusCodes = {
	 *     200 = "Return Podcasts list",
	 *     400 = "No show was founded."
	 *   }
	 * )
     *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     * @Rest\QueryParam(name="type", requirements="(FULL|EXTRACT|ADS)", nullable=true, description="Type de podcast.")
     *
     */
    public function podcastsAction(ParamFetcher $paramFetcher, $showId)
    {

        $em = $this->getDoctrine()->getManager();

    	$podcasts = $em->getRepository('EfreiReadyoWebradioBundle:Podcast')->findByShow(
    		$showId,
            $paramFetcher->get("type"),
    		$paramFetcher->get("limit"), 
    		$paramFetcher->get("offset")
    	);


        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('list'))
            ->setVersion($this->version)
            ->setSerializeNull(true)
        );
        $view->setData($podcasts, 200);

        return $this->handleView($view);
    }
}
