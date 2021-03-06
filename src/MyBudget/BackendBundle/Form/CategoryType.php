<?php

namespace MyBudget\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', null, array('empty_value' => 'Selecciona...'));
        $builder->add('name');
        $builder->add('description', 'textarea');
    }

    public function getName()
    {
        return 'mybudget_backendbundle_categorytype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyBudget\CategoryBundle\Entity\Category',
        ));
    }
}
