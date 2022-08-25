<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // the add method accepts 3 parameters :
            // the first is the name of the entity property
            // the second one is the type of field
            // the third is an array of options to pass to the form.
            ->add('firstName', TextType::class, [
                'label' => 'Prénom*',
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom*',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email*',
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
//
//            ->add('profileImageFile', VichImageType::class, [
//                'label' => 'Photo de profil',
//                'required' => false
//            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
