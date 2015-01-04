<?php

namespace Efrei\Readyo\PalladiumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends Controller
{
    public function indexAction()
    {

    	$extractedDoc = $this->get('nelmio_api_doc.extractor.api_doc_extractor')->all();
		$htmlContent  = $this->get('nelmio_api_doc.formatter.simple_formatter')->format($extractedDoc);
		//simple_formatter
		//return new Response(json_encode($htmlContent), 200, array('Content-Type' => 'text/html'));
		//echo json_encode($jsonContent);

		return $this->render('EfreiReadyoPalladiumBundle:Api:api.html.twig', array(
			"JSON_DOC" => json_encode($htmlContent)
		));
    }

}


