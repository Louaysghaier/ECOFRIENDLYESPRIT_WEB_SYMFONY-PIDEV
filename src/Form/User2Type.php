<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomuser')
            ->add('prenomuser')
            ->add('mailuser')
            ->add('mdpuser')
            
            ->add('adressuser')
            ->add('classeuser')
            ->add('roleuser')
            //->add("SignUp",SubmitType::class)
            ->add('SignUp', SubmitType::class, [

                'label' => 'SignUp',
                'attr' => [
                    'class' => 'btn btn-primary float-right',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User2::class,
        ]);
    }
}
