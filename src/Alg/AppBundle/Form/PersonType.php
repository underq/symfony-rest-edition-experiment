<?php

namespace Alg\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array('required' => false))
            ->add('lastname', 'text')
            ->add('company_name', 'text', array('required' => false))
            ->add('address', 'text')
            ->add('city', 'text', array('required' => false))
            ->add('country', 'text')
            ->add('postal', 'text')
            ->add('phone1', 'text')
            ->add('phone2', 'text', array('required' => false))
            ->add('email', 'email')
            ->add('website', 'text', array('required' => false))
            ->add('siren', 'text', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Alg\AppBundle\Entity\Person'
        ));
    }

    public function getName()
    {
        return 'person';
    }
}
