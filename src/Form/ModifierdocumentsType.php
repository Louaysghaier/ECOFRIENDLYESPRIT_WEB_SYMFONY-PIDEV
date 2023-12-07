<?php

namespace App\Form;

use App\Entity\Documents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class modifierdocuments extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('document_name')
            ->add('document_Type', ChoiceType::class, [
                'choices' => [
                    'TD' => 'td',
                    'DEVOIR' => 'devoir',
                    'COURS' => 'cours',
                    'CoorigéDEVOIR' => 'coorigeedevoir',
                    
                ],
                'label' => 'Type de document',
                'placeholder' => 'Sélectionnez un type', 
            ])
            ->add('document')
    
            


            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    '1er année' => '1er année',
                    '2éme année' => '2er année',
                    '3éme année' => '3er année',
                    '4éme année' => '4er année',
                    '5éme année' => '5er année',
                    
                ],
                'label' => 'Niveau',
                'placeholder' => 'Sélectionnez Niveau', 
            ])
            

            ->add('semestre', ChoiceType::class, [
                'choices' => [
                    '1er semestre' => '1er semestre',
                    '2éme semestre' =>'2éme semestre',
                    
                    
                ],
                'label' => 'Semestre',
                'placeholder' => 'Sélectionnez Semestre', 
            ])
        
            
            
            ->add('modifier', type:SubmitType::class)
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
