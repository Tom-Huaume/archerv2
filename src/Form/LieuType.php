<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du lieu',
                'attr'=>[
                    'class'=>'form-control input-nom-lieu',
                    'placeholder'=>'Ex : Club des archers de Janzé'
                ]
            ])

            ->add('rue', TextType::class, [
                'label' => 'Rue',
                'attr'=>[
                    'class'=>'form-control input-rue-lieu',
                ]
            ])

            ->add('rue2', TextType::class, [
                'required' => false,
                'attr'=>[
                    'class'=>'form-control input-rue-lieu',
                    'placeholder'=>'(Factultatif)'
                ]
            ])

            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'attr'=>[
                    'class'=>'form-control input-cp-lieu',
                ]
            ])

            ->add('ville', TextType::class, [
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
            'data_class' => Lieu::class,
        ]);
    }
}
