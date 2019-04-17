<?php


namespace App\Form;


use App\Entity\SearchPartsTitle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPartsTitleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' , TextType::class, [
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder'=> 'titre du morceau'
                ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchPartsTitle::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }



}