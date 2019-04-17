<?php


namespace App\Form;


use App\Entity\SearchPartsStyles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPartsStylesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('styles' , TextType::class, [
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder'=> 'style de musique'
                ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchPartsStyles::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }
}