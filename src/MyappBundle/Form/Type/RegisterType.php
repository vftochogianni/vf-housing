<?php

namespace MyappBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('name', TextType::class, array(
                    'label' => 'Name',
                    'label_attr' => array(
                            'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('email', EmailType::class, array(
                    'label' => 'Email',
                    'label_attr' => array(
                            'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
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
            ->add('tel', TextType::class, array(
                    'label' => 'Phone number',
                    'label_attr' => array(
                            'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'required' => false
                ))
            ->add('question', TextType::class, array(
                    'label' => 'Security Question',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('answer', TextType::class, array(
                    'label' => 'Security Answer',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('register', SubmitType::class, array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
;
    }

    public function getName(){
        return 'register';
    }
}