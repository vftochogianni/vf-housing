<?php

namespace MyappBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('username', EmailType::class, array(
                    'label' => 'Email',
                    'label_attr' => array(
                            'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('next', SubmitType::class, array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
;
    }

    public function getName(){
        return 'forgot_password';
    }
}