<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('servicename', null, [

            'constraints' => [
                new NotBlank(['message' => 'check the chatbot for help!','allowNull' => true,
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Your service name should have at least {{ limit }} characters.',
                    'max' => 20,
                    'maxMessage' => 'Your service name should not exceed {{ limit }} characters.',
                ]),
            ],
        ])

        ->add('price', null, [

            'constraints' => [
                new NotBlank(['message' => 'check the chatbot for help!']),
                new Type(['type' => 'numeric', 'message' => 'Price must be a valid number.']),
            ],
        ])
        ->add('description', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'check the chatbot for help!',
                ]),
                new Length([
                    'min' => 10,
                    'minMessage' => 'Your description should have at least {{ limit }} characters.',
                    'max' => 80,
                    'maxMessage' => 'Your description should not exceed {{ limit }} characters.',
                ]),
            ],
        ])
            ->add('img', FileType::class, [
                'label' => 'Upload Image',
                'required' => false, // Set to true if the image is mandatory
                'data_class' => null,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Add img !',]),
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ]),
                ],
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}