<?php 

namespace Efrei\Readyo\PalladiumBundle\Service;

use Doctrine\ORM\EntityManager;
use Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\HeaderBag;

use Efrei\Readyo\PalladiumBundle\Exception\TopicException;

use Efrei\Readyo\MusicBundle\Entity\Music;
use Efrei\Readyo\MusicBundle\Entity\MusicPlayed;
use Efrei\Readyo\LiveBundle\Entity\LiveMessage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;

class PalladiumProcess
{
	private $_em;
	private $_jwtManager;
	private $_serializer;

	public function __construct(EntityManager $em, JWTManager $jwtManager, Serializer $serializer) {
		$this->_em = $em;
		$this->_jwtManager = $jwtManager;
		$this->_serializer = $serializer;

	}

	public function process(PalladiumMessage $m)	{
		$messages = array();

		switch($m->getTopic()) {

			case "fr/readyo/palladium/live/authenticate/check":
				
				$user = $this->checkCredentials($m->getData()->token, $m->getData()->userAgent, $m->getData()->ip);

				if($user != null)
					$messages[] = $this->generateMessage("fr/readyo/palladium/live/authenticate/success", json_encode(array(
						"userid" => $user->getId(),
						"username" => $user->getUsername(),
						"firstname" => $user->getFirstname(),
						"lastname" => $user->getLastname(),
						"picture" => $user->picture(),
					)), $m->getReference());
				else
					$messages[] = $this->generateMessage("fr/readyo/palladium/live/authenticate/fail", json_encode(array()), $m->getReference());

				break;

			case "fr/readyo/palladium/music/playing":

				if($m->getData()->playing == true && !empty($m->getData()->media))
	    			$this->storeMusic(
	    				$m->getData()->media->track->name, $m->getData()->media->track->spotify, 
	    				$m->getData()->media->artist->name, $m->getData()->media->artist->spotify, 
	    				$m->getData()->media->album->name, $m->getData()->media->album->spotify
	    			);

				break;

			case "fr/readyo/palladium/live/message/emit":

	    			$liveMessage = $this->storeLiveMessage($m->getData()->userid, $m->getData()->message);

	    			if($liveMessage)
		    			$messages[] = $this->generateMessage("fr/readyo/palladium/live/message/receive", $this->_serializer->serialize(
	    					$liveMessage,
	    					'json', 
	    					SerializationContext::create()
	    						->setGroups(array('live'))
	            				->setVersion("1.0")
	            				->setSerializeNull(true)
	            		), $m->getReference());

				break;
		}

		return $messages;
	}

	private function checkCredentials($token, $useragent, $ip) {
		

		$request = new Request();

		$request->request = new ParameterBag(array());
        $request->query = new ParameterBag(array());
        $request->server = new ServerBag(array("REMOTE_ADDR" => $ip));
        $request->headers = new HeaderBag(array("User-Agent" => $useragent, "Authorization" => "Token ".$token));

		$this->_jwtManager->setRequest($request);

		$t = new JWTUserToken();
    	$t->setRawToken($token);

    	$payload = $this->_jwtManager->decode($t);

    	// No token available
    	if(!$payload)
	    	return null;

	    return $this->_em->getRepository('EfreiReadyoUserBundle:User')->findOneByUsername($payload["username"]);
	}

	private function generateMessage($topic, $data, $reference=null) {
		
		$message =  new PalladiumMessage();
		$message->setTopic($this->retrieveTopic($topic));
		$message->setData($data);
		$message->setReference($reference);

		return $message;
	}

	public function storeLiveMessage($userid, $text) {

		$user = $this->_em->getRepository('EfreiReadyoUserBundle:User')->findOneById($userid);
		$schedules = $this->_em->getRepository('EfreiReadyoWebradioBundle:Schedule')->inDiffusion(10, 10);

		if(count($schedules) > 0) {


			$message = new LiveMessage();

			$message->setText($text);
			$message->setUser($user);
			$message->setSchedule($schedules[0]);

			$this->_em->persist($message);
			$this->_em->flush();

			return $message;
		}

		return null;
	}



	public function storeMusic($track_name, $track_spotify, $artist_name, $artist_spotify, $album_name, $album_spotify) {

		$currentMusic = $this->_em->getRepository('EfreiReadyoMusicBundle:Music')
									->findOneBy(array("trackSpotify" => $track_spotify));

		if(!$currentMusic) {
			$currentMusic = new Music();

			$currentMusic->setTrackName($track_name);
			$currentMusic->setTrackSpotify($track_spotify);

			$currentMusic->setArtistName($artist_name);
			$currentMusic->setArtistSpotify($artist_spotify);

			$currentMusic->setAlbumName($album_name);
			$currentMusic->setAlbumSpotify($album_spotify);

			$this->_em->persist($currentMusic);
		}


		$schedules = $this->_em->getRepository('EfreiReadyoWebradioBundle:Schedule')->inDiffusion(1, 1);

		if(count($schedules) > 0) {

			$playlist = new MusicPlayed();
			$playlist->setSchedule($schedules[0]);
			$playlist->setMusic($currentMusic);
			$playlist->setPlayedAt(new \DateTime());

			$this->_em->persist($playlist);
			
		}

		$this->_em->flush();

	}

	public function retrieveTopic($path) {

		$topic = $this->_em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneByPath($path); 

            if(!$topic)
                throw new TopicException($path);

        return $topic;
/*
        if(!array_key_exists($path, $this->topics)) {
        
            $topic = $this->_em->getRepository('EfreiReadyoPalladiumBundle:Topic')->findOneByPath($path); 

            if(!$topic)
                throw new TopicException($path);

            $this->topics[$topic->getPath()] = $topic;
        }

        return $this->topics[$path];
*/
    }
}

