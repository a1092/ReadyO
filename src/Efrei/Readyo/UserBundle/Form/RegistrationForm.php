<?php

namespace Efrei\Readyo\UserBundle\Form;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Validator\Constraints\Regex;

class RegistrationForm extends AbstractType
{

    private $api;

    public function __construct($api = true) {
        $this->api = $api;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
            ->add('username', 'text')
            ->add('firstname', 'text')
            ->add('lastname', 'text')
            ->add('gender', new GenderType())
            ->add('birthdate', 'date', array('widget' => 'single_text', "format" => "yyyy-MM-dd"))
            ->add('pictureFile', 'file', array(
                'required' => false
            ))
        ;

        if($this->api) {
            $builder
                ->add('plainPassword', 'password')
                ->add('email', 'email', array(
                    'constraints' => new Regex(array(
                        'pattern' => '/[-0-9a-zA-Z.+_]+@(efrei|esigetel|groupe-efrei)+\.[a-zA-Z]{2,4}/',
                        'match'   => true,
                        'message' => 'Email Efrei obligatoire.',
                    ))
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
                ))
                ->add('email', 'email')
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
