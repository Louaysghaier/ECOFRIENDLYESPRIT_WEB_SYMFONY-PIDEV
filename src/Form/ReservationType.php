<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lieuDepart', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le lieu de départ doit contenir au moins {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('lieuArrive', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le lieu d\'arrivée doit contenir au moins {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('dateTicket', DateTimeType::class)
            // Ajoutez d'autres champs si nécessaire...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez les options du formulaire si nécessaire...
        ]);
    }
    
}