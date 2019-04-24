<?php

namespace App\Controller;


use App\Entity\SearchUserInfluences;
use App\Entity\SearchUserStyles;
use App\Entity\SearchUsername;
use App\Entity\Users;
use App\Form\SearchInfluencesType;
use App\Form\SearchStylesType;
use App\Form\SearchUsernameType;
use App\Form\UsersType;
use App\Repository\PartsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UsersController extends AbstractController
{
    const AVATAR_DIRECTORY = "img/avatar_directory";

    /**
     * @Route("/users", name="users_index")
     */
    public function index(UsersRepository $usersRepository) : Response
    {

        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'users' => $usersRepository->findAll(),
            'user' => $this->getUser(),

        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/registration" , name="registration_user")
     */
    public function registration(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();;



        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid())
        {

            $avatar_img = $form->get('avatar')->getData();

            // condition si le champ avatar_img est vide ,mettre l'image 'profil_default' par défaut :

            if($_FILES['users']['name']['avatar'] == "")
            {


                $user->setAvatar('profil_default.png');

            }
            else {

                $avatar_name = $this->generateUniqueFileName();
                $avatar_img->move(self::AVATAR_DIRECTORY, $avatar_name.".png");
                $user->setAvatar($avatar_name.".png");


            }







            $encod = $encoder->encodePassword($user, $user->getPassword());
            $user->setRoles('ROLE_USER');
            $user->setPassword($encod);
            $EmptyManager = $this->getDoctrine()->getManager();
            $EmptyManager->persist($user);
            $EmptyManager->flush();

            return $this->render('users/login.html.twig',
                ['form' => $form->createView(),
                 'user' => $this->getUser(), ]);
        }

        return $this->render('/users/registration.html.twig' ,
            ['form' => $form->createView(),
                'user' => $this->getUser(),]);
    }

    /**
     * @Route("/connexion" , name="security_connexion")
     */
     public function connexion(Request $request , AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('users/login.html.twig',
            ['error' => $error ,
                'user' => $this->getUser(),]);

    }

    /**
     * @Route("/logout" , name="logout_user")
     */
    public function logout()
    {

    }

    /**
     * @Route("/", name="check")
     */

    public function check()
    {

        if (true == $this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('parts_index');
        }

        if (true == $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('users_search');
        }

        else {
            return $this->redirectToRoute('registration_user');

        }
    }


    /**
     * @return Response
     * @Route("/profile/{id}/show_profile" , name="user_profile" , methods={"GET"})
     */
    public function profile_user($id ,PartsRepository $partsRepository, UsersRepository $usersRepository) : Response
    {


        $user = $usersRepository->find($id);

        return $this->render('users/show_profile.html.twig',
            ['users' => $user,
             'user' => $this->getUser(),
             'id_user' => $id ,
             'parts' => $partsRepository->findBy(
                  ["author"=> $user]
              )  ]);

    }

    /**
     * @Route("/{id}/edit_profile", name="edit_urprofile" , methods={"GET" , "POST"})
     *
     */
    public function edit_profile(Request $request, Users $user , UserPasswordEncoderInterface $encoder) : Response
    {


        $avatarUser = $user->getAvatar() ;
        $user->setAvatar('');


        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            $avatar_img = $form->get('avatar')->getData();


            if($_FILES['users']['name']['avatar'] == "")
            {

                $user->setAvatar($avatarUser);

            }
            else {

                $avatar_name = $this->generateUniqueFileName();
                $avatar_img->move(self::AVATAR_DIRECTORY, $avatar_name.".png");
                $user->setAvatar($avatar_name.".png");


            }





            $encod = $encoder->encodePassword($user, $user->getPassword());
            $user->setRoles('ROLE_USER');
            $user->setPassword($encod);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parts_index', [
                'id' => $this->getUser()->getId(),
            ]);

        }
        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/search", name="users_search")
     */
    public function search(Request $request,UsersRepository $usersRepository) : Response
    {

        $search_u = new SearchUsername();
        $search_s = new SearchUserStyles();
        $search_i = new SearchUserInfluences();




        $SearchFormBy_username = $this->createForm(SearchUsernameType::class ,$search_u);
        $SearchFormBy_username->handleRequest($request);
        $data = $SearchFormBy_username->getData();
        //dump($data);

        $SearchFormBy_style = $this->createForm(SearchStylesType::class, $search_s);
        $SearchFormBy_style->handleRequest($request);
        $data2 = $SearchFormBy_style->getData();

        //dump($data2);

        $SearchFormBy_influences = $this->createForm(SearchInfluencesType::class, $search_i);
        $SearchFormBy_influences->handleRequest($request);
        $data3 = $SearchFormBy_influences->getData();
       // dump($data3);





        if($SearchFormBy_username->isSubmitted())
        {

           // $search_u->setSearchUsername($data->getUsername());
            ////$users = $usersRepository->FindUserByName($search_u)->getResult();

            if($data->getUsername()) {
                $search_u->setSearchUsername($data->getUsername());

                $users = $usersRepository->FindUserByName($search_u)->getResult();

                if (!empty($users)) {
                    $users = array_merge($users);
                }

            }
        }
        if($SearchFormBy_style->isSubmitted())
        {
            if($data2->getStyles()) {
                $search_s->setSearchStyle($data2->getStyles());

                $users = $usersRepository->FindUserByStyles($search_s)->getResult();

                if (!empty($users)) {
                    $users = array_merge($users);
                }

            }

        }


        if($SearchFormBy_influences->isSubmitted())
        {
           // $search_i->setSearchInfluence($data3->getInfluences());
           // $users = $usersRepository->FindUserByInfluences($search_i)->getResult();

            if($data3->getInfluences()) {
                $search_i->setSearchInfluence($data3->getInfluences());

                $users = $usersRepository->FindUserByInfluences($search_i)->getResult();

                if (!empty($users)) {
                    $users = array_merge($users);
                }

            }

        }

        if(!$SearchFormBy_influences->isSubmitted() and !$SearchFormBy_style->isSubmitted() and !$SearchFormBy_username->isSubmitted())
        {
            $users = $usersRepository->findAll();
        }

        return $this->render('users/search.html.twig', [
            'controller_name' => 'UsersController',
            'users' => $users,
            'user' => $this->getUser(),
            'form_U' => $SearchFormBy_username->createView(),
            'form_S' => $SearchFormBy_style->createView(),
            'form_I' => $SearchFormBy_influences->createView(),

        ]);
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


}




