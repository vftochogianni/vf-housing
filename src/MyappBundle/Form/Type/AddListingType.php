<?php

namespace MyappBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,array(
                    'label' => 'Title',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('address', TextType::class,array(
                    'label' => 'Address',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('city', TextType::class,array(
                    'label' => 'City',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('country', CountryType::class,array(
                    'label' => 'Country',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('state', ChoiceType::class, array(
                    'choices'  => array(
                        'Sale' => 'Sale',
                        'Rent' => 'Rent'
                    ),
                    // *this line is important*
                    'multiple' =>false,
                    'expanded' => true,
                    'choices_as_values' => true,
                    'label' => false,

                    'choice_attr' => function() {
                        return ['style' => 'margin-left:2%;'];
                    },
                    'attr' => array(
                        'class' => 'form-control'
                    )

                ))
            ->add('status', ChoiceType::class, array(
                    'choices'  => array(
                        'Furnished' => 'Furnished',
                        'Semi-furnished' => 'Semi-furnished',
                        'Unfurnished' => 'Unfurnished'
                    ),
                    // *this line is important*
                    'multiple' =>false,
                    'expanded' => true,
                    'choices_as_values' => true,
                    'label' => false,
                    'choice_attr' => function() {
                        return ['style' => 'margin-left:2%;'];
                    },
                    'attr' => array(
                        'class' => 'form-control'
                    )

                ))
            ->add('m2',NumberType::class,array(
                    'label' => 'Size (m2)',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('price',MoneyType::class,array(
                    'currency' => null,
                    'label' => 'Price',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('currency',CurrencyType::class,array(
                    'label' => 'Currency',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('bedrooms', ChoiceType::class, array(
                    'choice_list'  => new ChoiceList(
                        array(0,1,2,3,4,5,6,7,8,9,10),
                        array(0,1,2,3,4,5,6,7,8,9,10)
                    ),
                    'label' => 'Bedrooms',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('floor', ChoiceType::class, array(
                    'choice_list'  => new ChoiceList(
                        array(0,1,2,3,4,5,6,7,8,9,10),
                        array(0,1,2,3,4,5,6,7,8,9,10)
                    ),
                    'label' => 'Floor',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
            ->add('description', TextareaType::class, array(
                    'label' => 'Description',
                    'label_attr' => array(
                        'class' => 'form-control-label'
                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => '1200'
                    )
                ))
            ->add('submit',SubmitType::class,array(
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
        ;

    }
    public function getName(){
        return 'add_listing';
    }
}