<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', PasswordType::class, array('label'=>'New password:'))
            ->add('confirmPassword', PasswordType::class, array('label'=>'Confirm password:'))
        ;
    }
    
        public function configureOptions(OptionsResolver $resolver)
    {
        
        
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\PasswordResetData',
        ));
    }
}
