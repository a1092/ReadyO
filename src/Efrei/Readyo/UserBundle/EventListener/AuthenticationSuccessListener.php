<?php

namespace Efrei\Readyo\UserBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {

        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof \Efrei\Readyo\UserBundle\Entity\User) {
            return;
        }

        // $data['token'] contains the JWT
/*
        $data['data'] = array(
            'roles' => $user->getRoles(),
        );
*/



        $event->setData($data);
    }
}