<?php

namespace MyBudget\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TargetType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        //Array of years
        $current = date('Y');
        $years = range($current-1, $current+1);

        $builder->add('month', 'date', array(
            'format' => 'MMMM yyyy dd',
            'years' => $years,
            'days' => array(15),
            'invalid_message' => 'Formato de fecha no válido.'
        ));
        $builder->add('amount', null, array(
            'invalid_message' => 'Formato no válido.'
        ));
        $builder->add('points', null, array(
           'invalid_message' => 'Formato no válido.' 
        ));
    }

    public function getName()
    {
        return 'mybudget_backendbundle_targettype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyBudget\BackendBundle\Entity\Target',
        ));
    }
}
