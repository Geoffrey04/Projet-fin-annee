<?php

namespace App\Form;


use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,[
                'label' => 'Pseudo/Nom :',
                'attr' => [
                    'placeholder'=> 'Votre Prénom ou pseudo'
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'E-mail :',
                'attr' => [
                    'placeholder'=> 'Votre e-mail'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message :',
                'attr' => [
                    'placeholder'=> 'Votre message '
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet :',
                'attr' => [
                    'placeholder'=> 'Objet du message...'
                ]
            ]);

}
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,

        ]);
    }
}
