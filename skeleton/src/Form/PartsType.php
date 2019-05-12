<?php

namespace App\Form;

use App\Entity\Parts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true ,
                'label'=> 'Titre :' ,
                'attr' =>[
                    'placeholder'=> 'titre de la musique...',
                ]

            ])
            ->add('styles', TextType::class, [
                'label'=> 'Style(s) :' ,
                'attr' =>[
                    'placeholder'=> 'style de musique...',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label'=> 'Type :' ,
                'choices'=> [
                    'Tablature' => 'Tablature',
                    'Partition' => 'Partition'
                ],
                  'attr'=>[
                    'placeholder' => 'Type de morceau...',
    ]
            ])
            ->add('pictures', FileType::class, [
                'label'=> 'Image :' ,
                'required'=> true,
                'attr'=>[
                    'accept'=> "image/png", "image/jpeg", "image/jpg",
                    'placeholder'=> 'image...',
                ]
            ])
            ->add('Groupe', TextType::class, [
                'label'=> 'Groupe :' ,
                'attr'=> [
                    'placeholder'=> "Nom de l'artiste ou groupe...",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parts::class,

        ]);
    }
}
