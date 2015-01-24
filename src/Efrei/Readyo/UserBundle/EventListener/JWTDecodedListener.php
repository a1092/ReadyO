<?php

namespace Efrei\Readyo\UserBundle\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Doctrine\ORM\EntityManager;

class JWTDecodedListener
{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {

        if (!($request = $event->getRequest())) {
            return;
        }

        $payload = $event->getPayload();
        $request = $event->getRequest();
	
/*
        if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
            $event->markAsInvalid();
        }
*/
        if (!isset($payload['plateform']) || $payload['plateform'] !== $request->headers->get('User-Agent')) {
            $event->markAsInvalid();
        }

        $expiredAt = new \Datetime();
        $expiredAt->setTimestamp($payload['exp']);

        $token = $this->em->getRepository('EfreiReadyoUserBundle:AuthToken')->findOneBy(array(
            "ip" => $payload['ip'], 
            "plateform" => $payload['plateform'],
            "expiredAt" => $expiredAt,
            "isRevoked" => false
        ));

        if(!$token) {
            $event->markAsInvalid();
        }
    }
}
