<?php

namespace App\Form;

use App\Entity\SearchUserInfluences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchInfluencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('influences' , TextType::class, [
                'required' => false,
                'label' => 'Influence :',
                'attr' =>[
                    'placeholder'=> 'artiste ou groupe...'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchUserInfluences::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }
}
