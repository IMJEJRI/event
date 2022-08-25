<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class KindType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // the add method accepts 3 parameters :
        // the first is the name of the entity property
        // the second one is the type of field
        // the third is an array of options to pass to the form.
        $builder
            ->add('label', TextType::class, [
                'label' => 'Label'
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ],
                'label' => 'Enregistrer'
            ]);
    }
}