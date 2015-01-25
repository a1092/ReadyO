<?php

namespace Efrei\Readyo\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResettingFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', 'password');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Efrei\Readyo\UserBundle\Entity\User',
            'intention'  => 'resetting',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return '';
    }
}
