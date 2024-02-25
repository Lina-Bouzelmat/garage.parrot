<?php

namespace App\Form;
    
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VehiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Marque
        $builder->add('marque', TextType::class, [
            'label' => 'marque*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez une marque'
                ])
            ]
        ]);
        //Modele
        $builder->add('modele', TextType::class, [
            'label' => 'modele*      ',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez un modele'
                ])
            ]
        ]);
        //Carburant
        $builder->add('carburant', TextType::class, [
            'label' => 'carburant*    ',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez un type de carburant'
                ])
            ]
        ]);
        //Prix
        $builder->add('prix', NumberType::class, [
            'label' => 'prix*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez un prix'
                ])
            ]
        ]);
        //Kilometrage
        $builder->add('kilometrage', NumberType::class, [
            'label' => 'kilometrage*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez le kilometrage'
                ])
            ]
        ]);
        //porte
        $builder->add('porte', NumberType::class, [
            'label' => 'porte*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez une valeur'
                ])
            ]
        ]);
        //place
        $builder->add('place', NumberType::class, [
            'label' => 'place*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champs ne peut être vide, entrez une valeur'
                ])
            ]
        ]);
        //details
        $builder->add('details', TextareaType::class, [
            'label' => 'details du vehicule'
        ]);
        // Statut
        $builder->add('isPublished', CheckboxType::class, [
            'label' => 'Publier l\'annonce'
        ]);
        //publishedVh
        $builder->add('publishedVh', DateTimeType::class, [
            'label' => 'date',
        ]);
        // Bouton Envoyer
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Enregistrer'
        ));
        $builder->add('imageFile', VichImageType::class, [
            'label' => 'Image du véhicule',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ]
        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}