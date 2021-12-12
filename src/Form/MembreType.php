<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numLicence', TextType::class, [
                'required' => false,
                'attr'=>[
                    'class'=>'form-control input-numlic-membre'
                ]
            ])

            ->add('nom', TextType::class, [
                'attr'=>[
                    'class'=>'form-control input-nom-membre'
                ]
            ])

            ->add('prenom', TextType::class, [
                'attr'=>[
                    'class'=>'form-control input-nom-membre'
                ]
            ])

            ->add('dateNaissance', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'attr'=>[
                    'class'=>'form-control input-date-membre'
                ]
            ])

            ->add('telMobile', TelType::class, [
                'attr'=>[
                    'class'=>'form-control input-tel-membre'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr'=>[
                    'class'=>'form-control input-mail-membre'
                ]
            ])

            ->add('lateralite', ChoiceType::class, [
                'attr'=>[
                    'class'=>'form-control input-lat-membre'
                ],
                'choices'  => [
                    'D' => 'D',
                    'G' => 'G'
                ],
            ])

            ->add('sexe', ChoiceType::class, [
                'attr'=>[
                    'class'=>'form-control input-sexe-membre'
                ],
                'choices'  => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme'
                ],
            ])

            ->add('categorieAge', TextType::class, [
                'attr'=>[
                    'class'=>'form-control input-cat-membre'
                ]
            ])

            ->add('typeLicence', TextType::class, [
                'attr'=>[
                    'class'=>'form-control input-type-membre'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
