<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieuDestination', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => Lieu::class,
                'label' => 'Lieu de l\'évènement',
                'query_builder' => function (LieuRepository $l) {
                    return $l->createQueryBuilder('l')
                        ->where('l.list = 1')
                        ->orderBy('l.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => '',
                'attr'=>[
                    'class'=>'form-select font-input input-lieu-event',
                ]
            ])

            ->add('nom', TextType::class, [
                'label' => 'Quel titre voulez-vous donner à votre évènement ?',
                'attr'=>[
                    'class'=>'form-control font-input',
                ]
            ])

            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Décrivez votre évènement',
                'attr'=>[
                    'class'=>'form-control font-input-little',
                    'placeholder'=>'(Facultatif)',
                ]
            ])

            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de début de l\'évènement',
                'html5' => true,
                'widget' => 'single_text',
                'attr'=>[
                    'class'=>'font-input',
                ]
            ])

            ->add('dateHeureFin', DateTimeType::class, [
                'label' => 'Date et heure de fin de l\'évènement',
                'html5' => true,
                'widget' => 'single_text',
                'attr'=>[
                    'class'=>'font-input',
                ]
            ])

            ->add('dateHeureLimiteInscription', DateTimeType::class, [
                'label' => 'Date et heure limite pour s\'inscrire',
                'html5' => true,
                'widget' => 'single_text',
                'attr'=>[
                    'class'=>'font-input',
                ]
            ])

            ->add('nbInscriptionsMax', IntegerType::class, [
                'required' => false,
                'label' => 'Places disponibles',
                'attr'=>[
                    'class'=>'form-control input-inscr-event',
                    'min' => 1
                ]
            ])

            ->add('tarif', TextType::class, [
                'required' => false,
                'label' => 'Voulez-vous indiquer un prix ?',
                'attr'=>[
                    'class'=>'form-control input-prix-event',
                    'placeholder'=>'(Facultatif)',
                ]
            ])

            ->add('photo', FileType::class, [
                'mapped'=>false,
                'label' => 'Souhaitez-vous ajouter une photo ?',
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

            //_____Formulaire ville_____
            ->add('nomlieu', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Nom du lieu',
                'attr'=>[
                    'class'=>'form-control input-nom-lieu',
                    'placeholder'=>'Ex : Club des archers de Janzé'
                ]
            ])

            ->add('rue', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Rue',
                'attr'=>[
                    'class'=>'form-control input-rue-lieu',
                ]
            ])

            ->add('rue2', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'attr'=>[
                    'class'=>'form-control input-rue-lieu',
                    'placeholder'=>'(Factultatif)'
                ]
            ])

            ->add('codePostal', TextType::class, [
                'mapped'=>false,
                'required' => false,
                'label' => 'Code postal',
                'attr'=>[
                    'class'=>'form-control input-cp-lieu',
                ]
            ])

            ->add('ville', TextType::class, [
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
            'data_class' => Evenement::class,
        ]);
    }
}
