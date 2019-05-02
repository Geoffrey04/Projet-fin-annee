<?php


namespace App\Form;


use App\Entity\Parts;
use App\Entity\SearchPartsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPartsTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type' , ChoiceType::class, [
                'required' => false,
                'label' => 'Type :' ,
                'attr' =>[
                    'placeholder'=> 'type tablature ou partitions',
                    'class' => 'form-control-sm',
                ],
                'choices'=> [
        'Tablature' => 'Tablature',
        'Partition' => 'Partition'
    ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchPartsType::class,
            'method' => 'get',
            'csrf_protection' => false ,
        ]);
    }
}