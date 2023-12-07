<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];

        if ($user) {
            $builder
                ->add('idUser', null, [
                    'data' => $user->getIduser(),
                    'mapped' => false, // Exclude this field from being mapped to the entity
                ]);
        }

        $builder
        ->add('subject', ChoiceType::class, [
            'choices' => [
                'Sale & Exchange' => 'Sale & Exchange',
                'Coding Problems' => 'Coding Problems',
                'Esprit Problems' => 'Esprit Problems',
            ],
            'attr' => [
                'style' => 'width: 100%; height: 30px;',
            ],
            'label' => 'Subject',
        ])
            //->add('title')
            ->add('title', TextType::class, [
                'attr' => [
                    'style' => 'width: 100%; height: 30px; color: #FFFAF0;',
                    //'style' => 'padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; width: 250px; transition: border-color 0.3s ease;', 
                ],
            ])
            ->add('imagePost', FileType::class, [
                'label' => ' Upload Image', // Définissez le libellé personnalisé ici
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'The file is too large. Maximum allowed size is 10MB.',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Please upload a valid image file.',
                    ]),
                ],
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'form-control', // Ajoutez la classe Bootstrap pour le style du bouton
                    'style' => 'background-color: transparent', 
                    
                ],
            ])
            //->add('descriptionPost')
            ->add('descriptionPost', TextType::class, [
                'attr' => [
                    'style' => 'width: 100%; height: 80px;',
                ],
            ])
            ->add('Share', SubmitType::class, [
                'label' => 'Share',
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'user' => null,
        ]);
    }
}
