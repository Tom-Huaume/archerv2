<?php

namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clubDefaut', ChoiceType::class, [
                'mapped' => false,
                'choices'  => [
                    'Club de Liffré' => true,
                    'Autre adresse' => false,
                ],
                'attr'=>[
                    'class'=>'form-select input-choice-address',
                ],
            ])

            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr'=>[
                    'class'=>'form-control input-nom-lieu',
                    'placeholder'=>'Ex : Allé-retour Liffré-Vern ',
                ]
            ])

            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Commentaire',
                'attr'=>[
                    'class'=>'form-control font-input-little',
                    'placeholder'=>'(Facultatif)',
                ]
            ])

            ->add('dateHeureDepart', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'attr'=>[
                    'class'=>'font-input',
                ]
            ])

            ->add('nbPlaces', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr'=>[
                    'class'=>'form-control input-inscr-event',
                ]
            ])

            ->add('typeVoiture', TextType::class, [
                'required' => false,
                'label' => 'Type de voiture',
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'(Facultatif)',
                ]
            ])

            ->add('couleurVoiture', TextType::class, [
                'required' => false,
                'label' => 'Couleur de la voiture',
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'(Facultatif)',
                ]
            ])


            //_____Formulaire ville_____
            ->add('nomLieuDepart', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Nom du lieu',
                'attr'=>[
                    'class'=>'form-control input-nom-lieu',
                    'placeholder'=>'Ex : Club des archers de Janzé'
                ]
            ])

            ->add('rueLieuDepart', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Rue',
                'attr'=>[
                    'class'=>'form-control input-rue-lieu',
                ]
            ])

            ->add('rue2LieuDepart', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Rue2',
                'attr'=>[
                    'class'=>'form-control input-rue-lieu',
                    'placeholder'=>'(Factultatif)'
                ]
            ])

            ->add('codePostalLieuDepart', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Code postal',
                'attr'=>[
                    'class'=>'form-control input-cp-lieu',
                ]
            ])

            ->add('villeLieuDepart', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Ville',
                'attr'=>[
                    'class'=>'form-control input-ville-lieu',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
