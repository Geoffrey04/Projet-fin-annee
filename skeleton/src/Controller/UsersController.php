<?php

namespace App\Controller;

use App\Entity\Parts;
use App\Entity\Users;
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
            'users' => $usersRepository->findAll()
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

            return $this->render('users/login.html.twig', ['form' => $form->createView()]);
        }

        return $this->render('/users/registration.html.twig' , ['form' => $form->createView()]);
    }

    /**
     * @Route("/connexion" , name="security_connexion")
     */
     public function connexion(Request $request , AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('users/login.html.twig', ['error' => $error]);

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
              'parts' => $partsRepository->findBy(
                  ["author"=> $this->getUser()->getId()]
              )  ]);

    }


}
