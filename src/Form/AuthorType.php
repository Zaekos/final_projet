<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom'
    ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'Prénom'
    ])
            ->add('birthDate', DateType::class, [
                'years' => range(date('Y'), date('Y') - 500),
                'required'=>true,
                'label' => 'Date de naissance'])
            ->add('deathDate', DateType::class, [
                'years' => range(date('Y'), date('Y') - 500),
                'required'=>false,
                'label' => 'Date de mort'])
            ->add('biography', TextType::class, [
                'required' => true
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
