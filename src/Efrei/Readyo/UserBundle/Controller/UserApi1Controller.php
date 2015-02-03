<?php

namespace Efrei\Readyo\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\Request;

use JMS\Serializer\SerializationContext;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

use Efrei\Readyo\UserBundle\Entity\User;

use Efrei\Readyo\UserBundle\Form\UserForm;
use Efrei\Readyo\UserBundle\Form\RegistrationForm;
use Efrei\Readyo\UserBundle\Form\ResettingFormType;

use FOS\RestBundle\Request\ParamFetcher;





/*
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;


use FOS\UserBundle\Event\FilterUserResponseEvent;





use Symfony\Component\Security\Core\SecurityContext;



use JMS\Serializer\SerializationContext;
*/


class UserApi1Controller extends FOSRestController
{
    private $version = "1.0";
    

    /**
     * @ApiDoc(
     *   section = "User",
     *   input = "Efrei\Readyo\UserBundle\Form\LoginForm",
     *   ressource = true,
     *   statusCodes = {
     *      200="OK"
     *   }
     * )
     *
     * @Rest\View()
     */
    public function loginAction()
    {

    }


    /**
     * @ApiDoc(
     *   section = "User",
     *   ressource = true,
     *   statusCodes = {
     *      200="OK"
     *   }
     * )
     *
     * @Rest\QueryParam(name="token", nullable=false, description="User token")
     *
     * @Rest\View()
     */
    public function logoutAction(Request $request, ParamFetcher $paramFetcher) {


        $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');
        $jwtManager->setRequest($request);

        $token = new JWTUserToken();
        $token->setRawToken($paramFetcher->get("token"));

        $payload = $jwtManager->decode($token);

        // No token available
        if(!$payload) {
            return new JsonResponse(['message' => 'Bad token.'], 400);
        }


        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EfreiReadyoUserBundle:User')->findOneByUsername($payload["username"]);

        $expiredAt = new \Datetime();
        $expiredAt->setTimestamp($payload['exp']);

        $em->getRepository('EfreiReadyoUserBundle:AuthToken')->revoke($user, $payload['ip'], $payload['plateform'], $expiredAt);

        return new JsonResponse([], 200);
    }


    /**
	 * @ApiDoc(
	 *   section = "User",
     *   input = "Efrei\Readyo\UserBundle\Form\RegistrationForm",
	 *   ressource = true,
	 *   statusCodes = {
	 * 		200="OK"
	 *   }
	 * )
	 *
     * @Rest\View(statusCode = Codes::HTTP_BAD_REQUEST)
     */
    public function registerAction(Request $request)
    {
       
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');


        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setCreatedAt(new \Datetime());

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm(new RegistrationForm(true), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $userManager->updateUser($user);

            return new JsonResponse(['message' => 'User created.'], 201);
        }
        
        return $form;        
    }

    /**
     * @ApiDoc(
     *   section = "User",
     *   ressource = true,
     *   statusCodes = {
     *      200="OK"
     *   }
     * )
     *
     * @Rest\View()
     *
     */
    public function registerConfirmAction(Request $request,  $token)
    {

        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return new JsonResponse(["message" => 'The user with confirmation token "'.$token.'" does not exist'], 200);
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            return new JsonResponse([], 200);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return new JsonResponse([], 400);
    }

    /**
     * @ApiDoc(
     *   section = "User",
     *   ressource = true,
     *   statusCodes = {
     *      200="OK",
     *      400="Not authenticated."
     *   }
     * )
     */
    public function profileAction(Request $request)
    {
       
        $user = $this->get('security.context')->getToken()->getUser();

        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(array('details'))
            ->setVersion($this->version)
            ->setSerializeNull(true)
        );
        $view->setData($user, 200);

        return $this->handleView($view);
    }


    /**
     * @ApiDoc(
     *   section = "User",
     *   input = "Efrei\Readyo\UserBundle\Form\UserForm",
     *   ressource = false,
     *   statusCodes = {
     *      200="OK"
     *   }
     * )
     *
     * @Rest\View()
     */
    public function editProfileAction(Request $request)
    {
       
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UserForm(), $user, array(
            'method' => "POST",
            'csrf_protection' => false
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $user->setModifiedTime(new \DateTime());

            $em->persist($user);
            $em->flush();

            $view = $this->view();
            $view->setSerializationContext(SerializationContext::create()
                ->setGroups(array('details'))
                ->setVersion($this->version)
                ->setSerializeNull(true)
            );
            $view->setData($user, 200);

        } else {
            
            $view = $this->view();
            $view->setSerializationContext(SerializationContext::create()
                ->setSerializeNull(true)
            );
            $view->setData($form, 400);
        }

        return $this->get('fos_rest.view_handler')->handle($view);     
    }



    /**
     * @ApiDoc(
     *   section = "User",
     *   ressource = false,
     *   statusCodes = {
     *      200="OK"
     *   }
     * )
     *
     * @Rest\QueryParam(name="username", nullable=false, description="Username")
     *
     * @Rest\View()
     */
    public function passwordRequestAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return new JsonResponse([], 400);
        }
/*
        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new JsonResponse(["message" => "Password already requested."], 400);
        }
*/
        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }
        
        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        return new JsonResponse([], 200);
    }

     /**
     * @ApiDoc(
     *   section = "User",
     *   input = "Efrei\Readyo\UserBundle\Form\ResettingFormType",
     *   ressource = true,
     *   statusCodes = {
     *      200="OK"
     *   }
     * )
     *
     * @Rest\View()
     */
    public function passwordResetAction(Request $request,  $token)
    {   

        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return new JsonResponse(['message' => 'The user with "confirmation token" does not exist for value "'.$token.'".'], 400);
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return new JsonResponse([], 400);
        }

        $form = $this->createForm(new ResettingFormType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                return new JsonResponse(['message' => 'Password updated.'], 200);
            }

            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return new JsonResponse($response, 200);
        }

        return $form;
    }

}
