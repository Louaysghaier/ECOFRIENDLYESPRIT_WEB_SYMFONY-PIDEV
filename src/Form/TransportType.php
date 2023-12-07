<?php

namespace App\Form;

use App\Entity\Transport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbrePlaces', null, [
                'label' => 'Nombre de places',
                'attr' => ['class' => 'gray-input']
            ])
            ->add('typeTransport', null, [
                'label' => 'Type de transport',
                'attr' => ['class' => 'gray-input']
            ])
            ->add('disponibilite', ChoiceType::class, [
                'choices' => [
                    'En service' => 'en service',
                    'Hors service' => 'hors service',
                    'En cours de maintenance' => 'en cours de maintenance',
                ],
                'label' => 'Disponibilité',
                'attr' => ['class' => 'gray-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }

    
}