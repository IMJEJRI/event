<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Kind;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // the add method accepts 3 parameters :
        // the first is the name of the entity property
        // the second one is the type of field
        // the third is an array of options to pass to the form.
        $builder
            ->add('title')
            ->add('datetime')

            ->add('kind', EntityType::class, [
                'class' => Kind::class
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,

            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
