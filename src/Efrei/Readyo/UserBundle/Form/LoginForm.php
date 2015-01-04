<?php

namespace Efrei\Readyo\UserBundle\Form;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;


class LoginForm extends AbstractType
{

    protected $routeName;
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct(Container $container, $class)
    {
        $request = $container->get('request');
        $this->routeName = $request->get('_route');
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
                ->add('username', 'text')
                ->add('password', 'text')
              
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));            
 
    }

    public function getName()
    {
        return '';
    }
}
