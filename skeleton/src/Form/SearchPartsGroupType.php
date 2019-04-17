<?php


namespace App\Form;


use App\Entity\SearchPartsGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPartsGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupe' , TextType::class, [
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder'=> "Nom de l'artiste ou du groupe"
                ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchPartsGroup::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }
}