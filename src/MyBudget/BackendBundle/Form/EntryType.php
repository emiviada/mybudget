<?php

namespace MyBudget\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', 'entity', array(
            'class' => 'CategoryBundle:Category',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
            },
            'empty_value' => 'Selecciona...'
        ));
        $builder->add('date_entry', 'date', array(
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'invalid_message' => 'Formato de fecha no válido.'
        ));
        $builder->add('haber');
        $builder->add('value', null, array(
            'invalid_message' => 'Formato no válido.'
        ));
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
