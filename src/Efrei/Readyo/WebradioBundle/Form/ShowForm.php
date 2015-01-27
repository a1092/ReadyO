<?php

namespace Efrei\Readyo\WebradioBundle\Form;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;


class ShowForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    	$builder
                ->add('title', 'text', array('required' => true))
                ->add('shortTitle', 'text', array('required' => true))
                ->add('subTitle', 'textarea', array('required' => true))
                ->add('description', 'textarea', array('required' => true))
                ->add('type', 'choice', array(
                    'required' => true,
                    'choices' => array(
                        '' => '',
                        'MUSIC' => 'Musique',
                        'FLASH' => 'Flash Info',
                        'INTERVIEW' => 'Interview',
                        'MAGAZINE' => 'Magazine',
                        'DEBATE' => 'Débat',
                        'ADS' => 'Publicité',
                        'NONE' => 'Hors catégorie',
                    )
                ))
                ->add('bigPictureFile', "file", array(
                    'required' => false
                ))
                ->add('smallPictureFile', "file", array(
                    'required' => false
                ))
                ->add('isPublish', 'checkbox', array('required' => false))
                
            ;        
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Efrei\Readyo\WebradioBundle\Entity\Show',
        ));    
    }

    public function getName()
    {
        return '';
    }
}
