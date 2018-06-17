<?php

namespace MyappBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder

            ->add('password',RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array(
                        'label' => 'Password',
                        'label_attr' => array(
                            'class'=> 'form-control-label'
                        ),
                        'attr' => array(
                            'class' => 'form-control'
                        )
                    ),
                    'second_options' => array(
                        'label' => 'Repeat Password',
                        'label_attr' => array(
                            'class'=> 'form-control-label'
                        ),
                        'attr' => array(
                            'class' => 'form-control'
                        )
                    )
                ))
            ->add('change password', SubmitType::class, array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
        ;
    }

    public function getName(){
        return 'changePassword';
    }
}