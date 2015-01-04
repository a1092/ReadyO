<?php

namespace Efrei\Readyo\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Doctrine\ORM\EntityManager;
use Efrei\Readyo\UserBundle\Entity\AuthToken;

class JWTCreatedListener
{

	protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }



    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {

        if (!($request = $event->getRequest())) {
            return;
        }


        $payload       = $event->getData();
        $user = $event->getUser();

        $payload['ip'] = $request->getClientIp();
        $payload['plateform'] = $request->headers->get('User-Agent');

        $token = new AuthToken();
        $token->setIp($payload['ip']);

        $expiredAt = new \Datetime();
        $expiredAt->setTimestamp($payload['exp']);
        $token->setExpiredAt($expiredAt);

        $token->setPlateform($payload['plateform']);
		$token->setUser($event->getUser());

		$this->em->getRepository('EfreiReadyoUserBundle:AuthToken')->revoke($event->getUser(), $payload['ip'], $payload['plateform'], $expiredAt);

		$this->em->persist($token);
        $this->em->flush();

        $event->setData($payload);
    }
}
