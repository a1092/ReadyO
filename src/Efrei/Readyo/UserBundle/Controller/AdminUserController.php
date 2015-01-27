<?php

namespace Efrei\Readyo\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Efrei\Readyo\UserBundle\Entity\User;
use Efrei\Readyo\UserBundle\Form\UserForm;
use Efrei\Readyo\UserBundle\Form\RegistrationForm;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;

use FOS\UserBundle\Form\Type\ResettingFormType;


class AdminUserController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('EfreiReadyoUserBundle:Admin:Dashboard.html.twig', array(
        //	'articles' => $articles
        ));
    }

    public function listAction(Request $request)
    {

    	$em = $this->getDoctrine()->getManager();

    	$search = array();

    	$searchForm = $this->searchForm()->getForm();
    	$searchForm->handleRequest($request);

        if ($searchForm->isValid()) {
        	$search = $searchForm->getData();
        }


    	$users = $em->getRepository('EfreiReadyoUserBundle:User')->search(
    		$search
    	);

        return $this->render('EfreiReadyoUserBundle:Admin:List.html.twig', array(
        	'users' => $users,
        	'searchForm' => $searchForm->createView()
        ));
    }


    public function addAction(Request $request)
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

        $form = $this->createForm(new RegistrationForm(false), $user, array(
            'method' => "POST",
            'csrf_protection' => true
        ));


        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $userManager->updateUser($user);

            return $this->redirect($this->generateUrl('admin_user_list'));
        }
        
        return $this->render('EfreiReadyoUserBundle:Admin:New.html.twig', array(
        	'form' => $form->createView()
        ));
    }


    public function editAction(Request $request, $userid) {
        
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('EfreiReadyoUserBundle:User')->findOneById($userid);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }


        $form = $this->createForm(new UserForm(false), $user, array(
            'method' => "POST",
            'csrf_protection' => true
        ));


        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $user->setModifiedTime(new \DateTime());
            $userManager->updateUser($user);

            return $this->redirect($this->generateUrl('admin_user_show', array("userid" => $user->getId())));
        }

        return $this->render('EfreiReadyoUserBundle:Admin:Edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function showAction($userid) {

    	$em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('EfreiReadyoUserBundle:User')->find($userid);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

         return $this->render('EfreiReadyoUserBundle:Admin:Show.html.twig', array(
        	'user' => $user
        ));
    }

    public function promotionAction(Request $request, $userid) {

    	$em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('EfreiReadyoUserBundle:User')->find($userid);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $action = $request->request->get('action');
        $role = $request->request->get('role');

        if($action == "promote") {
        	$user->addRole($role);
        	$em->persist($user);
        }

        if($action == "demote") {
        	$user->removeRole($role);
        	$em->persist($user);
        }


        $em->flush();

    	return new Response();

    }

    private function searchForm() {

    	$form = $this->createFormBuilder();

    	return $form
    		->add('username', 'text', array('required' => false))
    		->add('email', 'text', array('required' => false))
    		->add('lastname', 'text', array('required' => false))
    		->add('groups', 'choice', array(
    			'choices' => array('' => 'Groupe', 'ROLE_USER' => 'Auditeur', 'ROLE_ANIMATEUR' => 'Animateur', 'ROLE_ADMIN' => 'Administrateur'),
    			'required' => false
    		))
    		->add('valid', 'choice', array(
    			'choices' => array('' => 'Valide ?', "true" => 'Oui', "false" => 'Non'),
    			'required' => false
    		))
    	;
    }
}
