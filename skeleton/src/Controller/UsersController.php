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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UsersController extends AbstractController
{
    /*create folder for register the avatar profile of user*/
    const AVATAR_DIRECTORY = "img/avatar_directory";

    /**
     * @Route("/users", name="users_index")
     */
    public function index(Request $request, UsersRepository $usersRepository, PaginatorInterface $paginator): Response
    {

        $users = $paginator->paginate($usersRepository->findAll(),
            $request->query->getInt('page',1),6);

        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'users' => $users,
            'user' => $this->getUser(),


        ]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/registration" , name="registration_user")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder) : Response
    {
        /*Register a new user with form*/
        $user = new Users();;
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar_img = $form->get('avatar')->getData();

            /*if the user doesn't record a profile picture , default value : "profil_default.png"*/
            if ($_FILES['users']['name']['avatar'] == "") {
                $user->setAvatar('profil_default.png');
            } else {
                $avatar_name = $this->generateUniqueFileName();
                $avatar_img->move(self::AVATAR_DIRECTORY, $avatar_name . ".png");
                $user->setAvatar($avatar_name . ".png");
            }
            $PasswordEncode = $encoder->encodePassword($user, $user->getPassword());
            $user->setRoles('ROLE_USER');
            $user->setPassword($PasswordEncode);
            $EmptyManager = $this->getDoctrine()->getManager();
            $EmptyManager->persist($user);
            $EmptyManager->flush();

            return $this->render('users/login.html.twig',
                ['form' => $form->createView(),
                    'user' => $this->getUser(),
                ]);
        }

        return $this->render('/users/registration.html.twig',
            ['form' => $form->createView(),
                'user' => $this->getUser(),
            ]);
    }
    /**
     * @Route("/connexion" , name="security_connexion")
     */
    public function connexion(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('users/login.html.twig',
            ['error' => $error,
             'user' => $this->getUser(),
            ]);
    }

    /**
     * @Route("/logout" , name="logout_user")
     */
    public function logout()
    {}
    /**
     * @Route("/", name="check")
     */
    public function check()
    {
        /*check 'role' of the user connects.*/
        if (true == $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('parts_index');
        }
        if (true == $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('parts_index');
        } else {
            return $this->redirectToRoute('registration_user');

        }
    }
    /**
     * @return Response
     * @Route("/profile/{id}/show_profile" , name="user_profile" , methods={"GET"})
     */
    public function profile_user($id,Request $request, PartsRepository $partsRepository, UsersRepository $usersRepository , PaginatorInterface $paginator): Response
    {
        /*Create view of user profile with his description and list of his score drums set*/
        $user = $usersRepository->find($id);

        //$parts = $paginator->paginate($partsRepository->findPartsByAuthor($user)->getResult(),
        //$request->query->getInt('page',1), 8);
        $role = $this->getUser()->getRoles();

        return $this->render('users/show_profile.html.twig',
            ['users' => $user,
                'user' => $this->getUser(),
                'id_user' => $id,
                'role' => $role[0],
                'parts' => $partsRepository->findBy(
                    ["author" => $user]
                )]);
    }
    /**
     * @Route("/profile/{id}/edit_profile", name="edit_urprofile" , methods={"GET" , "POST"})
     *
     */
    public function edit_profile(Request $request, Users $user, UserPasswordEncoderInterface $encoder): Response
    {
        /*Edit user profile by form*/
        $avatarUser = $user->getAvatar();
        $user->setAvatar('');
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar_img = $form->get('avatar')->getData();


            if ($_FILES['users']['name']['avatar'] == "") {
                $user->setAvatar($avatarUser);
            } else {
                $avatar_name = $this->generateUniqueFileName();
                $avatar_img->move(self::AVATAR_DIRECTORY, $avatar_name . ".png");
                $user->setAvatar($avatar_name . ".png");
            }
            $PasswordEncode = $encoder->encodePassword($user, $user->getPassword());
            $user->setRoles('ROLE_USER');
            $user->setPassword($PasswordEncode);
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
     * @Route("/profile/search", name="users_search")
     *
     */
    public function search(Request $request, UsersRepository $usersRepository , PaginatorInterface $paginator): Response
    {
        $users = $paginator->paginate($usersRepository->findAll(), $request->query->getInt('page', 1), 5);

        $search_u = new SearchUsername();
        $SearchFormBy_username = $this->createForm(SearchUsernameType::class, $search_u);
        $SearchFormBy_username->handleRequest($request);
        $data = $SearchFormBy_username->getData();

        if ($SearchFormBy_username->isSubmitted()) {
            if ($data->getUsername()) {
                $search_u->setSearchUsername($data->getUsername());
                $users = $paginator->paginate($usersRepository->FindUserByName($search_u)->getResult(),
                    $request->query->getInt('page' , 1), 5
                );

            }
        }

        $search_s = new SearchUserStyles();
        $SearchFormBy_style = $this->createForm(SearchStylesType::class, $search_s);
        $SearchFormBy_style->handleRequest($request);
        $data2 = $SearchFormBy_style->getData();
        //dump($data2);

        if ($SearchFormBy_style->isSubmitted()) {
            if ($data2->getStyles()) {
                $search_s->setSearchStyle($data2->getStyles());
                $users = $paginator->paginate($usersRepository->FindUserByStyles($search_s)->getResult(),
                    $request->query->getInt('page', 1), 5);

            }
        }

        $search_i = new SearchUserInfluences();
        $SearchFormBy_influences = $this->createForm(SearchInfluencesType::class, $search_i);
        $SearchFormBy_influences->handleRequest($request);
        $data3 = $SearchFormBy_influences->getData();
        // dump($data3);

        if ($SearchFormBy_influences->isSubmitted()) {
            if ($data3->getInfluences()) {
                $search_i->setSearchInfluence($data3->getInfluences());
                $users = $paginator->paginate($usersRepository->FindUserByInfluences($search_i)->getResult(),
                    $request->query->getInt('page', 1),6);

            }
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