<?php

namespace Efrei\Readyo\UserBundle\Form;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;


class UserForm extends AbstractType
{

    private $api;

    public function __construct($api = true) {
        $this->api = $api;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
                ->add('firstname', 'text', array('required' => true))
                ->add('lastname', 'text', array('required' => true))
                ->add('gender', new GenderType(), array('required' => true))
                ->add('birthdate', 'date', array('widget' => 'single_text', "format" => "yyyy-MM-dd", "required" => true))
                ->add('pictureFile', 'file', array(
                    'required' => false
                ))
            ;  

        if($this->api) {
            $builder
                ->add('plainPassword', 'password', array(
                    'required' => false
                ))
            ;

        } else {
            $builder
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                    'required' => false
                ))
            ;
        }      
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Efrei\Readyo\UserBundle\Entity\User',
            'csrf_protection' => false
        ));    
    }

    public function getName()
    {
        return '';
    }
}
