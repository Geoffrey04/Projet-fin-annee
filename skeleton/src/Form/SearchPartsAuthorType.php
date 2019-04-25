<?php


namespace App\Form;


use App\Entity\SearchPartsAuthor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Users;

class SearchPartsAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author' , EntityType::class, [
                'class' => Users::class,
                'required' => false,
                'label' => 'Auteur :' ,
                'attr' =>[
                    'placeholder'=> "nom de l'auteur de la publication"
                ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => false,
            'data_class' => SearchPartsAuthor::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }

}