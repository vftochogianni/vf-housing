<?php

namespace MyappBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('answer', TextType::class, array(
                    'label' => 'Security Answer',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('submit', SubmitType::class, array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
        ;
    }

    public function getName(){
        return 'question';
    }
}