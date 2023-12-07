<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix', null, [
                'label' => 'Prix',
                'attr' => ['style' => 'margin-bottom: 10px; background-color: rgba(169, 169, 169, 0.2); border: 1px solid gray; color: gray; padding: 5px; border-radius: 5px;']
            ])
            ->add('lieuDepart', null, [
                'label' => 'Lieu de départ',
                'attr' => ['style' => 'margin-bottom: 10px; background-color: rgba(169, 169, 169, 0.2); border: 1px solid gray; color: gray; padding: 5px; border-radius: 5px;']
            ])
            ->add('lieuArrive', null, [
                'label' => 'Lieu d\'arrivée',
                'attr' => ['style' => 'margin-bottom: 10px; background-color: rgba(169, 169, 169, 0.2); border: 1px solid gray; color: gray; padding: 5px; border-radius: 5px;']
            ])
            ->add('dateTicket', null, [
                'label' => 'Date du ticket',
                'attr' => ['style' => 'margin-bottom: 10px; background-color: rgba(169, 169, 169, 0.2); border: 1px solid gray; color: gray; padding: 5px; border-radius: 5px;']
            ])
            ->add('statutTicket', ChoiceType::class, [
                'choices' => [
                    'Réservé' => 'réservé',
                    'Non réservé' => 'non réservé',
                ],
                'label' => 'Statut du ticket',
                'attr' => ['style' => 'margin-bottom: 10px; background-color: rgba(169, 169, 169, 0.2); border: 1px solid gray; color: gray; padding: 5px; border-radius: 5px;']
            ])
            ->add('idTransport', EntityType::class, [
                'class' => 'App\Entity\Transport',
                'choice_label' => 'typeTransport',
                'label' => 'Type de transport',
                'attr' => ['style' => 'margin-bottom: 10px; background-color: rgba(169, 169, 169, 0.2); border: 1px solid gray; color: gray; padding: 5px; border-radius: 5px;']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}