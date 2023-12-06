<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Ajoutez cette ligne pour importer DateType
use Symfony\Component\Form\Extension\Core\Type\FileType;//importer image
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;  
use Symfony\Component\Validator\Constraints\Length;  
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;










class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           // ->add('nomEvent')
           ->add('nomevent', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le nom de l\'événement ne peut pas être vide.']),
                new Length([
                    'min' => 5,
                    'minMessage' => 'Le nom de l\'événement doit avoir au moins {{ limit }} caractères.',
                ]),
                // Ajoutez d'autres contraintes au besoin
            ],
        ])

        
     ->add('lieuevent', TextType::class, [
        'label' => 'Lieu de l\'événement',
        'attr' => [
            'class' => 'form-control input-control',
            'placeholder' => 'Saisissez le lieu de l\'événement',
            'novalidate' => 'novalidate',
        ],
        'constraints' => [
            new NotNull(['message' => 'Le lieu de l\'événement ne peut pas être nul.']),
            // Ajoutez vos autres contraintes au besoin
            new Length([
              'min' => 5,
              'minMessage' => 'Le lieu de l\'événement doit avoir au moins {{ limit }} caractères.',
            ]),
        ],
    ])

    ->add('prixticket', TextType::class, [
      'label' => 'Prix du ticket',
      'attr' => [
          'class' => 'input-control',
          'novalidate' => 'novalidate',
      ],
      'constraints' => [
          new NotNull([
              'message' => 'Le prix du ticket ne peut pas être nul.',
          ]),
          new NotBlank([
            'message' => 'Le prix du ticket ne peut pas être vide.',
        ]),
      ],
  ])
  ->add('datedebutevent', DateType::class, [
    'label' => 'Début de l\'événement',
    'widget' => 'single_text',
    'attr' => [
        'placeholder' => 'Saisissez la date de début de l\'événement',
        // Ajoutez vos autres options au besoin
    ],
    'constraints' => [
        new NotNull(['message' => 'La date de début de l\'événement ne peut pas être nulle.']),
        // Ajoutez vos autres contraintes au besoin
    ],
])
  
  ->add('duree', TextType::class, [
      'attr' => [
          'novalidate' => 'novalidate',
          // Ajoutez vos autres options au besoin
      ],
      'constraints' => [
          new NotNull(['message' => 'La durée de l\'événement ne peut pas être nulle.']),
          // Ajoutez vos autres contraintes au besoin
      ],
  ])
  
  ->add('nbmaxparticipant', TextType::class, [
    'label' => 'Nombre max de participants',
    'attr' => [
        'class' => 'input-control',
        'novalidate' => 'novalidate',
    ],
    'constraints' => [
        new NotNull([
            'message' => 'Le nombre max de participants ne peut pas être nul.',
        ]),
        // Ajoutez vos autres contraintes au besoin
    ],
])
  




->add('typeevent', ChoiceType::class, [
    'label' => 'Type Event',
    'attr' => [
        'novalidate' => 'novalidate',
        'class' => 'input-control',
    ],
    'choices' => [
        'Sport' => 'Sport',
        'Loisir' => 'Loisir',
        'Culture' => 'Culture',
    ],
    'constraints' => [
        new NotNull(['message' => 'Le type de l\'événement ne peut pas être nul.']),
    ],
])



->add('descriptionevent', TextType::class, [
  'attr' => [
      'novalidate' => 'novalidate',
      // Ajoutez vos autres options au besoin
  ],
  'constraints' => [
      new NotNull(['message' => 'La description de l\'événement ne peut pas être nulle.']),
      // Ajoutez vos autres contraintes au besoin
      new Length([
          'min' => 5,
          'minMessage' => 'La description de l\'événement doit avoir au moins {{ limit }} caractères.',
      ]),
  ],
])






->add('image', FileType::class, [
    'label' => 'Image',
    'mapped' => false,
    'required' => false,
   
])

            ->add('save', SubmitType::class);
           // ->add('imageFile', VichImageType::class, [
             //   'required' => false,
               // 'allow_delete' => true,
                //'download_uri' => true,
              //  'image_uri' => true,
            //]);
    
           
            
            //->add('iduser')
            //->add('valid')
            
        
           }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
