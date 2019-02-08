<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
			->add('file', FileType::class, ['label' => 'Image de cover (JPG ou PNG)'])
			->add('content', TextType::class)
			->add('created_at', DateType::class)
			->add('updated_at', DateType::class)
			->add('is_enabled', CheckboxType::class, ['required' => false])
			->add('nb_like', NumberType::class)
			->add('save', SubmitType::class, ['label' => 'Ajouter Article'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
			'data_class' => 'App\Entity\MyEntity'
		]);
    }
}
