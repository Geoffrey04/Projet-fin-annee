<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'=> "Pseudo :",
                'attr'=>[
                    'placeholder' => "nom d'utilisateur...",
                ]
            ])

            ->add('password' , PasswordType::class, [
                'label'=>'Mot de passe :',
                'attr'=>[
                    'placeholder'=>'mot de passe...'
    ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'label'=> 'Confirmez votre mot de passe :',
                'attr'=>[
                    'placeholder'=>'confirmez votre mot de passe..'
    ]
            ])

            ->add('mail', EmailType::class, [
                'label'=> 'Email :',
                'attr'=>[
                    'placeholder'=> 'adresse email...'
                ]
            ])

            ->add('city', TextType::class, [
                'label'=>'Ville :',
                'required' => false,
                'attr'=> [
                    'placeholder'=> 'votre ville...'
                ]
            ])

            ->add('age', IntegerType::class, [
                'label'=> 'Age :',
                'required' => false,
                'attr'=> [
                    'placeholder'=> 'votre age ...'
                ]
            ])

            ->add('influences', TextareaType::class, [
                'label'=> 'Influences :',
                'required' => false,
                'attr'=> [
                    'placeholder'=>'Vos artistes ou groupes ...'
                ]
            ])

            ->add('styles', TextareaType::class, [
                'label'=> 'Style(s) :',
                'required' => false,
                'attr'=> [
                    'placeholder'=>'Votre/vos style(s) de musique ...'
                ]
            ])

            ->add('avatar', FileType::class, [
                'label'=>'Avatar/Photo de profil :',
                'required'=> false,
                'attr'=>[
                    'accept'=>"image/png, image/jpeg, image/jpg",
                ]
            ])

            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary',
                ]
            ])
        ;

        $builder
            ->get('avatar');

    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'method' => 'post',
        ]);
    }
}
