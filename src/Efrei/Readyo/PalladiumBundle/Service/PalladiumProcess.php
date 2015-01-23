<?php 

namespace Efrei\Readyo\PalladiumBundle\Service;

use Doctrine\ORM\EntityManager;
use Efrei\Readyo\PalladiumBundle\Entity\Message;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\HeaderBag;

use Efrei\Readyo\PalladiumBundle\Exception\TopicException;

use Efrei\Readyo\MusicBundle\Entity\Music;
use Efrei\Readyo\MusicBundle\Entity\MusicPlayed;

class PalladiumProcess
{
	private $_em;
	private $_jwtManager;

	public function __construct(EntityManager $em, JWTManager $jwtManager) {
		$this->_em = $em;
		$this->_jwtManager = $jwtManager;

	}

	public function process(Message $m)	{
		$messages = array();

		switch($m->getTopic()) {

			case "fr/readyo/palladium/live/authenticate/check":
				
				$user = $this->checkCredentials($m->getData()->token, $m->getData()->userAgent, $m->getData()->ip);

				if($user != null)
					$messages[] = $this->generateMessage("fr/readyo/palladium/live/authenticate/success", array(
						"userid" => $user->getId(),
						"username" => $user->getUsername(),
						"firstname" => $user->getFirstname(),
						"lastname" => $user->getLastname(),
						"picture" => $user->picture(),
					), $m->getReference());
				else
					$messages[] = $this->generateMessage("fr/readyo/palladium/live/authenticate/fail", array(), $m->getReference());

				break;

			case "fr/readyo/palladium/music/playing":

				if($m->getData()->playing == true && !empty($m->getData()->media))
	    			$this->storeMusic(
	    				$m->getData()->media->track->name, $m->getData()->media->track->spotify, 
	    				$m->getData()->media->artist->name, $m->getData()->media->artist->spotify, 
	    				$m->getData()->media->album->name, $m->getData()->media->album->spotify
	    			);

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
		
		$message =  new Message();
		$message->setTopic($this->retrieveTopic($topic));
		$message->setData(json_encode($data));
		$message->setReference($reference);

		return $message;
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

