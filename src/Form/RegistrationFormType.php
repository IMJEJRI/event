<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // the add method accepts 3 parameters :
            // the first is the name of the entity property
            // the second one is the type of field
            // the third is an array of options to pass to the form.
            ->add('firstName', TextType::class, [
                'label'=> 'Prénom*'
            ])
            ->add('lastName', TextType::class, [
                'label'=> 'Nom*'
            ])
            ->add('email', EmailType::class, [
                'label' => false
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, options: [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Mot de passe*',
                    'attr' => [
                        'placeholder'=> 'Mot de passe !'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe*',
                    'attr' => [
                        'placeholder'=> 'Confirmez votre mot de passe !'
                    ]
                ],
                'invalid_message' => 'Mot de passe incorrect',
                'attr' => ['autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe!',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
