<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class Event1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Titre de l\'évènement'
                ]
            ]) 
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Description',
                ]
            ])
            ->add('longitude', NumberType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Longitude',
                    'novalidate' => 'Le format saisie est invalide.'
                ]
            ])
            ->add('latitude', NumberType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Latitude'
                ]
            ])
            ->add('formattedAddress', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('locale', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Pays (fr-en-de...)'
                ]
            ])
            ->add('date', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Date (yyyy-mm-dd)'
                ]
            ])
            ->add('activityType', TextType::class, [
                'attr' => [
                    'class' => 'form-control text-white',
                    'placeholder' => 'Type d\'évènement'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
