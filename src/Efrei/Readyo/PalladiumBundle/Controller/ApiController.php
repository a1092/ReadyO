<?php

namespace Efrei\Readyo\PalladiumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\HeaderBag;


use Efrei\Readyo\MusicBundle\Entity\Music;
use Efrei\Readyo\MusicBundle\Entity\MusicPlayed;
use Efrei\Readyo\LiveBundle\Entity\LiveMessage;


use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;

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

    public function restartAction(Request $request) {
    	$em = $this->getDoctrine()->getManager();

    	/* Réinitialisation des connexions d'applications */
    	$applications = $em->getRepository('EfreiReadyoPalladiumBundle:Application')
        					->findAll();

        foreach($applications as $app) {
        	$app->setConnected(false);
        	$em->persist($app);
        }
        $em->flush();

        $topics = array();
        foreach($em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findAll() as $topic) {
        	$topics[] = array(
        		"path" => $topic->getPath(),
        		"log" => $topic->getLog()
        	);
        }

        return new JsonResponse(array(
        	"topics" => $topics
        ), 200);
    }


    public function connectAppAction(Request $request) {

    	$em = $this->getDoctrine()->getManager();

    	if(!$request->request->has('privateKey'))
    		return new JsonResponse(array("message" => "AppId is missing."), 400);

    	$privateKey = $request->request->get('privateKey');

        $application = $em->getRepository('EfreiReadyoPalladiumBundle:Application')
        					->findOneByPrivateKey($privateKey);

        if(!$application)
        	return new JsonResponse(array("message" => "Application authentication failed."), 400);

        if(!$application->isActive())
        	return new JsonResponse(array("message" => "Application is inactive."), 400);

        if($application->isConnected() && !$application->getMulticonnexion()) {
        	return new JsonResponse(array("message" => "Application already connected."), 400);
        }

        $application->setLastActivity(new \DateTime());
        $application->setConnected(true);
        $em->persist($application);
        $em->flush();

    	return new JsonResponse(array(
    		"id" => $application->getId(),
    		"name" => $application->getName()
    	), 200);
    }

    public function disconnectAppAction(Request $request) {

    	$em = $this->getDoctrine()->getManager();

    	if(!$request->request->has('appId'))
    		return new JsonResponse(array("message" => "AppId is missing."), 400);

    	$appId = $request->request->get('appId');

        $application = $em->getRepository('EfreiReadyoPalladiumBundle:Application')
        					->findOneById($appId);

    	$application->setLastActivity(new \DateTime());
        $application->setConnected(false);
        $em->persist($application);
        $em->flush();

        return new JsonResponse(array(
    		"id" => $application->getId(),
    		"name" => $application->getName()
    	), 200);
    }

    public function logAction(Request $request) {

    	$em = $this->getDoctrine()->getManager();

    	$msg = new PalladiumMessage();


    	if(!$request->request->has('topic'))
    		return new JsonResponse(array("message" => "Topic is missing."), 400);


    	$topic = $em->getRepository('EfreiReadyoPalladiumBundle:Topic')
        					->findOneByPath($request->request->get('topic'));

        if(!$topic)
        	return new JsonResponse(array("message" => "Topic not founded."), 400);

    	$msg->setTopic($topic);


    	if(!$request->request->has('application'))
    		return new JsonResponse(array("message" => "Application is missing."), 400);


    	$application = $em->getRepository('EfreiReadyoPalladiumBundle:Application')
        					->findOneById($request->request->get('application'));

        if(!$application)
        	return new JsonResponse(array("message" => "Application not founded."), 400);

    	$msg->setApplication($application);





    	if(!$request->request->has('data'))
    		return new JsonResponse(array("message" => "Data is missing."), 400);
    	
    	$msg->setData($request->request->get('data'));



    	if($request->request->has('reference'))
    		$msg->setReference($request->request->get('reference'));


        $em->persist($msg);
        $em->flush();

        return new JsonResponse(array(), 200);
    }



    public function checkCredentialAction(Request $request) {

    	

    	if(!$request->request->has('ip'))
    		return new JsonResponse(array("message" => "IP is missing."), 400);

    	if(!$request->request->has('userAgent'))
    		return new JsonResponse(array("message" => "User-Agent is missing."), 400);

    	if(!$request->request->has('token'))
    		return new JsonResponse(array("message" => "Token is missing."), 400);


    	$ip = $request->request->get('ip');
    	$useragent = $request->request->get('userAgent');
    	$token = $request->request->get('token');

    	$jwtManager = $this->get("lexik_jwt_authentication.jwt_manager");

    	$req = new Request();

		$req->request = new ParameterBag(array());
        $req->query = new ParameterBag(array());
        $req->server = new ServerBag(array("REMOTE_ADDR" => $ip));
        $req->headers = new HeaderBag(array("User-Agent" => $useragent, "Authorization" => "Token ".$token));

		
		$jwtManager->setRequest($req);
		$t = new JWTUserToken();
    	$t->setRawToken($token);

    	$payload = $jwtManager->decode($t);
    	
    	// No token available
    	if(!$payload)
	    	return new JsonResponse(array(), 401);

	    $em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('EfreiReadyoUserBundle:User')->findOneByUsername($payload["username"]);


	    return new JsonResponse(array(
			"userid" => $user->getId(),
			"username" => $user->getUsername(),
			"firstname" => $user->getFirstname(),
			"lastname" => $user->getLastname(),
			"picture" => $user->getPictureWebPath(),
		), 200);
    }



	public function musicPlayingAction(Request $request) {

		$em = $this->getDoctrine()->getManager();

		if(!$request->request->has('track_spotify'))
    		return new JsonResponse(array("message" => "Track Spotify is missing."), 400);

	    $currentMusic = $em->getRepository('EfreiReadyoMusicBundle:Music')
									->findOneBy(array("trackSpotify" => $request->request->get('track_spotify')));

		if(!$currentMusic) {
			$currentMusic = new Music();

			$currentMusic->setTrackName($request->request->get('track'));
			$currentMusic->setTrackSpotify($request->request->get('track_spotify'));

			$currentMusic->setArtistName($request->request->get('artist'));
			$currentMusic->setArtistSpotify($request->request->get('artist_spotify'));

			$currentMusic->setAlbumName($request->request->get('album'));
			$currentMusic->setAlbumSpotify($request->request->get('album_spotify'));

			$em->persist($currentMusic);
		}


		$schedules = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->inDiffusion(1, 1);

		if(count($schedules) > 0) {

			$playlist = new MusicPlayed();
			$playlist->setSchedule($schedules[0]);
			$playlist->setMusic($currentMusic);
			$playlist->setPlayedAt(new \DateTime());

			$em->persist($playlist);
			
		}

		$em->flush();

		return new JsonResponse(array(), 200);
    }


    public function liveMessageAction(Request $request) {

    	$em = $this->getDoctrine()->getManager();

        if(!$request->request->has('userid'))
            return new JsonResponse(array("message" => "Userid is missing."), 400);

        if(!$request->request->has('message'))
            return new JsonResponse(array("message" => "Message is missing."), 400);


        $userid = $request->request->get('userid');
        $text = $request->request->get('message');

    	$user = $em->getRepository('EfreiReadyoUserBundle:User')->findOneById($userid);
		$schedules = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->inDiffusion(10, 10);

		if(count($schedules) > 0) {


			$message = new LiveMessage();

			$message->setText($text);
			$message->setUser($user);
			$message->setSchedule($schedules[0]);

			$em->persist($message);
			$em->flush();

			return new JsonResponse(json_decode($this->get("jms_serializer")->serialize(
				$message,
				'json', 
				SerializationContext::create()
					->setGroups(array('live'))
					->setVersion("1.0")
					->setSerializeNull(true)
			)), 200);
		}

		return new JsonResponse(array("message" => "No schedule was founded"), 400);
    }

    public function scheduleStatusAction(Request $request) {

    	$em = $this->getDoctrine()->getManager();

    	// Récupération des programmes en cours
        $schedules = $em->getRepository('EfreiReadyoWebradioBundle:Schedule')->inDiffusion(1, 1);

        if(count($schedules) > 0) {

            if($schedules[0]->getDiffusedAt()->format('Y-m-d H:i') == (new \Datetime())->format('Y-m-d H:i')) {
            

                $channels = array();
                if(count($schedules[0]->getOutputs()) > 0) {
                    foreach($schedules[0]->getOutputs() as $output) {

                        $channels[] = array(
                            "channel" => $output->getId(),
                            "name" => $output->getName()
                        );
                    }
                }


                return new JsonResponse(array(
                	"action" => "starting",
                	"schedule" => json_decode($this->get("jms_serializer")->serialize($schedules[0], 'json', SerializationContext::create()
																->setGroups(array('live'))
																->setVersion("1.0")
																->setSerializeNull(true)
					)),
					"spotify" => $schedules[0]->getSpotifyUri(),
					"channels" => $channels,
				), 200);

            } else if($schedules[0]->getFinishedAt()->format('Y-m-d H:i') == (new \Datetime())->format('Y-m-d H:i')) {

            	$channels = array();
                if(count($schedules[0]->getOutputs()) > 0) {
                    foreach($schedules[0]->getOutputs() as $output) {

                        $channels[] = array(
                            "channel" => $output->getId(),
                            "name" => $output->getName()
                        );
                    }
                }


                return new JsonResponse(array(
                	"action" => "ending",
                	"schedule" => json_decode($this->get("jms_serializer")->serialize($schedules[0], 'json', SerializationContext::create()
																->setGroups(array('live'))
																->setVersion("1.0")
																->setSerializeNull(true)
					)),
					"spotify" => $schedules[0]->getSpotifyUri(),
					"channels" => $channels,
				), 200);
            }

        }

        return new JsonResponse(array("message" => "Aucun programme de prévu."), 400);
    }
}


