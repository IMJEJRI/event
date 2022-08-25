<?php

namespace App\Form;


use App\Entity\Kind;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Modifies the method of the form
            ->setMethod('GET')
            // Adds the desired fields to the search form
            ->add('title', TextType::class, [
                'label' => 'Titre evenement',
                'required' => false
            ])

            ->add('kinds', EntityType::class, [
                'label' => 'Genre',
                'class' => Kind::class,
                'placeholder' => '--Sélectionnez--',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Rechercher'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}