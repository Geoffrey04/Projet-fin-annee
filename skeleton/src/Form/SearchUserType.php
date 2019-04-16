<?php

namespace App\Form;

use App\Entity\SearchUser;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username' , TextType::class, [
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder'=> 'prenom ou pseudo'
                ]
        ])
            ->add('styles' , TextType::class, [
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder'=> 'style de musique'
                ]
            ])
            ->add('influences' , TextType::class, [
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder'=> 'artiste ou groupe'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchUser::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }


}
