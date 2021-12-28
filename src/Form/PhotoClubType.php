<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'mapped'=> false,
                'label' => false,
                'required' => false,
                'attr'=>[
                    'class'=>'form-control font-input',
                ],
                "constraints" => [
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
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
