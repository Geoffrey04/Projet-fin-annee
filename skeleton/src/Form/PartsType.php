<?php

namespace App\Form;

use App\Entity\Parts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('styles')
            ->add('type', ChoiceType::class, [
                'choices'=> [
                    'Tablature' => 'Tablature',
                    'Partition' => 'Partition'
                ]
            ])
            ->add('pictures', FileType::class, [

                'required'=> false,
                'attr'=>[
                    'accept'=> "image/png", "image/jpeg", "image/jpg"
                ]
            ])
            ->add('Groupe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parts::class,

        ]);
    }
}
