<?php

namespace Efrei\Readyo\UserBundle\Form;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;


class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
                ->add('firstname', 'text', array('required' => true))
                ->add('lastname', 'text', array('required' => true))
                ->add('gender', new GenderType(), array('required' => true))
                ->add('birthdate', 'date', array('widget' => 'single_text', "format" => "yyyy-MM-dd"))
            ;        
        
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
