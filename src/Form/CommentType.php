<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentType extends AbstractType
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
            
            //->add('descriptionComment')
            ->add('descriptionComment', TextType::class, [
                'attr' => [
                    //'class' => 'my-custom-input', // Ajouter des classes CSS
                    'style' => 'width: 100%; height: 80px;',
                     // Ajouter des styles en ligne
                    // Ajouter d'autres attributs HTML au besoin
                ],
            ])
            ->add('Share', SubmitType::class, [
                'label' => 'Share',
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'user' => null,
        ]);
    }
}
