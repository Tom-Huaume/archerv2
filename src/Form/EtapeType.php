<?php

namespace App\Form;

use App\Entity\Arme;
use App\Entity\Etape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtapeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Titre',
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])

            ->add('description', TextareaType::class, [
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'(Facultatif)',
                ]
            ])

            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'view_timezone' => 'Europe/Paris',
                'widget' => 'single_text',
                'attr'=>[
                    'class'=>'font-input',
                ]

            ])

            ->add('tarif', TextType::class, [
                'required' => false,
                'label' => 'Voulez-vous indiquer un prix ?',
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'(Facultatif)',
                ]
            ])

            ->add('nbInscriptionsMax', IntegerType::class, [
                'required' => false,
                'label' => 'Places disponibles',
                'attr'=>[
                    'class'=>'form-control',
                    'min' => 1
                ]
            ])

            ->add('arme', EntityType::class, [
                'required' => true,
                'mapped' => false,
                'expanded' => true,
                'multiple' => true,
                'class' => Arme::class,
                'label' => 'Armes autoris??es',
                'choice_label' => 'designation',
                'empty_data' => 'Veuillez s??lectionner au moins une arme',
                'placeholder' => 'Veuillez s??lectionner au moins une arme'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etape::class,
        ]);
    }
}
