<?php

namespace App\Controller;


use App\Entity\SearchInfluences;
use App\Entity\SearchStyles;
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
            return $this->redirectToRoute('parts_index');
        }

        else {
            return $this->redirectToRoute('parts_index');
        }
    }


    /**
     * @return Response
     * @Route("/show_profile" , name="user_profile" , methods={"GET"})
     */
    public function profile_user(PartsRepository $partsRepository) : Response
    {

        return $this->render('users/show_profile.html.twig',
            ['users' => $this->getUser(),
                'user' => $this->getUser(),
              'parts' => $partsRepository->findBy(
                  ["author"=> $this->getUser()->getId()]
              )  ]);

    }

    /**
     * @Route("/{id}/edit_profile", name="edit_urprofile" , methods={"GET" , "POST"})
     *
     */
    public function edit_profile(Request $request, Users $user) : Response
    {


        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
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
        $search_s = new SearchStyles();
        $search_i = new SearchInfluences();


        $SearchFormBy_username = $this->createForm(SearchUsernameType::class , $search_u);
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
            //dump($data);
            $search_u->setSearchUsername($data);
            //dump($data);
            $users = $usersRepository->FindUserByName($search_u)->getResult();
        }
        elseif($SearchFormBy_style->isSubmitted())
        {

            $search_s->setSearchStyle($data2->getStyles());
            $users = $usersRepository->FindUserByStyles($search_s)->getResult();
        }
        elseif($SearchFormBy_influences->isSubmitted())
        {
            $search_i->setSearchInfluence($data3->getInfluences());
            $users = $usersRepository->FindUserByInfluences($search_i)->getResult();
        }
        else
        {
            $users = $usersRepository->FindAll();
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




}




