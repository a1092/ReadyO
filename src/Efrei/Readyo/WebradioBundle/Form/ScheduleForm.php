<?php

namespace Efrei\Readyo\WebradioBundle\Form;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;


class ScheduleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
                ->add('title', 'text', array('required' => true))
                ->add('subTitle', 'textarea', array('required' => true))
                ->add('summary', 'textarea', array('required' => true))
                ->add('guests', 'textarea', array('required' => true))
                ->add('diffusedAt')
                ->add('duration')
                ->add('isPublish', "checkbox", array('required' => false))
                ->add('isLive', "checkbox", array('required' => false))
                ->add('spotifyUri', "text", array('required' => false))
                ->add('outputs', null, array('expanded' => "true", "multiple" => "true"))
            ;        
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Efrei\Readyo\WebradioBundle\Entity\Schedule',
        ));    
    }

    public function getName()
    {
        return '';
    }
}
