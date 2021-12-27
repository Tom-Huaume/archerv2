<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr'=>[
                    'class'=>'form-control font-input',
                ]
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Contenu',
                'attr'=>[
                    'class'=>'form-control font-input-great',
                ]
            ])

            ->add('photos', FileType::class, [
                'mapped'=>false,
                'label' => 'Souhaitez-vous ajouter une photo ?',
                'multiple' => true,
                'required' => false,
                'attr'=>[
                    'class'=>'form-control font-input',
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
