<?php

namespace MyBudget\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('category', null, array('empty_value' => 'Selecciona...'));
        $builder->add('date_entry');
        $builder->add('haber');
        $builder->add('value');
        $builder->add('comment', 'textarea');
    }

    public function getName()
    {
        return 'mybudget_backendbundle_entrytype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyBudget\EntryBundle\Entity\Entry',
        ));
    }
}
