<?php

namespace MyBudget\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('haber')
            ->add('value')
            ->add('comment')
            ->add('category');
    }

    public function getName()
    {
        return 'mybudget_backendbundle_entrytype';
    }
}
