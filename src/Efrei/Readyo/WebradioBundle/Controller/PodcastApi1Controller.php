<?php

namespace Efrei\Readyo\WebradioBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;


use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;


use Efrei\Readyo\WebradioBundle\Entity\PodcastComment;
use Efrei\Readyo\WebradioBundle\Form\PodcastCommentType;


use Efrei\Readyo\UserBundle\Entity\UserPicture;
use Efrei\Readyo\UserBundle\Form\UserPictureType;

class PodcastApi1Controller extends FOSRestController
{

    private $version = "1.0";

    /**
	 * @ApiDoc(
	 *   description = "Liste tous les podcasts.",
     *   resource = true,
     *   section = "Podcasts",
	 *   statusCodes = {
	 *     200 = "Liste des podcast.",
	 *   }
	 * )
	 *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     * @Rest\QueryParam(name="type", requirements="(FULL|EXTRACT|ADS)", nullable=true, description="Type de podcast.")
     *
     */
    public function listAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = array(
            "isPublish" => true
        );

        if($paramFetcher->get("type")) {
            $filter["type"] = $paramFetcher->get("type");
        }

    	$podcasts = $em->getRepository('EfreiReadyoWebradioBundle:Podcast')->findBy(
    		$filter,
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



    /**
	 * @ApiDoc(
	 *   description = "Retourne le podcast.",
     *   resource = false,
     *   section = "Podcasts",
	 *   statusCodes = {
	 *     200 = "Return Podcast",
	 *     400 = "No podcast was founded."
	 *   }
	 * )
     *
     */
    public function showAction($podcastId)
    {
        $em = $this->getDoctrine()->getManager();

    	$podcast = $em->getRepository('EfreiReadyoWebradioBundle:Podcast')->findOneBy(
    		array(
    			"id" => $podcastId,
    			"isPublish" => true
    		)
    	);

    	if(!$podcast)
    		$view = View::create("No Podcast was founded.", 400);
    	else {
            $view = $this->view();
            $view->setSerializationContext(SerializationContext::create()
                ->setGroups(array('details'))
                ->setVersion($this->version)
            );
            $view->setData($podcast, 200);
        }

        return $this->handleView($view);
    }


    /**
     * @ApiDoc(
     *   description = "Retourne les commentaires d'un podcast'",
     *   resource = true,
     *   section = "Podcasts",
     *   statusCodes = {
     *     200 = "Return comments list",
     *     400 = "No Podcast was founded."
     *   }
     * )
     *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", nullable=true, description="Index de la page.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="5", nullable=true, description="Nombre d'objets retournés.")
     *
     *
     */
    public function listCommentsAction(ParamFetcher $paramFetcher, $podcastId)
    {

        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('EfreiReadyoWebradioBundle:PodcastComment')->findBy(
            array("podcast" => $podcastId),
            array("publishAt"=>"DESC"),
            $paramFetcher->get("limit"), 
            $paramFetcher->get("offset")
        );

        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('details'))
            ->setVersion($this->version)
        );
        $view->setData($comments, 200);

        return $this->handleView($view);
    }



    /**
     * @ApiDoc(
     *   description = "Retourne les commentaires d'un podcast.",
     *   resource = true,
     *   section = "Podcasts",
     *   input = "Efrei\Readyo\WebradioBundle\Form\PodcastCommentType",
     *   statusCodes = {
     *     200 = "Return comments list",
     *     400 = "No Podcast was founded."
     *   }
     * )
     *
     * @Rest\View
     */
    public function postCommentsAction(Request $request)
    {

        $comment = new PodcastComment();

        $form = $this->createForm(new PodcastCommentType(), $comment, array(
            'method' => "POST",
            'csrf_protection' => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $comment->setUser(null);
            $comment->setIsPublish(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $view = View::create($comment);
        } else {
            //TODO return errors
            $view = View::create($form, 400);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

}
