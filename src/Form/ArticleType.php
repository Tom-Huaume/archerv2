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
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

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
                ],
                "constraints" => [
                    new All([
                        new File([
                            "maxSize" => "10M",
                            "mimeTypes" => [
                                "image/png",
                                "image/jpg",
                                "image/jpeg",
                                "image/gif"
                            ],
                            "mimeTypesMessage" => "Veuillez envoyer une image au format png, jpg, jpeg ou gif, de 10 mégas octets maximum"
                        ])
                    ])
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
