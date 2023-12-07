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
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Topic;


use Symfony\Component\Validator\Constraints\Image;

class DocumentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('documentName', null, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez entrer un nom de document.']),
            ],
            ])
            ->add('document_Type', ChoiceType::class, [
                'choices' => [
                    'TD' => 'td',
                    'DEVOIR' => 'devoir',
                    'COURS' => 'cours',
                    'CoorigéDEVOIR' => 'coorigeedevoir',
                    
                ],
                'label' => 'Type de document',
                'placeholder' => 'Sélectionnez un type', 
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un type de document.']),
                ],
            ])
            ->add('document', FileType::class, [
                'label' => 'Votre Document',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document image valide',
                        'notFoundMessage' => 'Le fichier ne peut pas être vide',
                    ]),
                ],
            ])
                   

              
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
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un niveau.']),
                ],  
            ])
            

            ->add('semestre', ChoiceType::class, [
                'choices' => [
                    '1er semestre' => '1er semestre',
                    '2éme semestre' =>'2éme semestre',
                    
                    
                ],
                'label' => 'Semestre',
                'placeholder' => 'Sélectionnez Semestre', 
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un semestre.']),
                ],
            ])
        



       // ...

->add('idtopic', EntityType::class, [
    'class' => 'App\Entity\Topic',
    'choice_label' => 'topicName', // Assuming 'topicName' is the property in your Topic entity
    'label' => 'Topic',
    'constraints' => [
        new NotBlank(['message' => 'Veuillez sélectionner un topic.']),
    ],
])

// ...



            ->add('brochure', FileType::class, [
                'label' => 'Brochure (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
          
           
            
            ->add('ajouter', type:SubmitType::class)
         
        
   ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
