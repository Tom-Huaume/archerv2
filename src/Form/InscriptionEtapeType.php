<?php

namespace App\Form;

use App\Entity\InscriptionEtape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionEtapeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $listeArmes = $options['data'];

        $builder
            ->add('commentaire', TextareaType::class, [
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Précisions concernant l\'inscription, option trispot, etc...',
                ]
            ])

            ->add('listeArmes', ChoiceType::class, [
                'mapped' => false,
                'choices' => $listeArmes,
                'attr'=>[
                    'class'=>'form-select'
                ],
                'empty_data' => ''

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            //'data_class' => InscriptionEtape::class,
        ]);
    }
}
