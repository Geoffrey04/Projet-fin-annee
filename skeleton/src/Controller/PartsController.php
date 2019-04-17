<?php

namespace App\Controller;

use App\Entity\Parts;
use App\Entity\Users;
use App\Form\PartsType;
use App\Repository\PartsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parts")
 */
class PartsController extends AbstractController
{
    /**
     * @Route("/", name="parts_index", methods={"GET"})
     */
    public function index(PartsRepository $partsRepository): Response
    {
        return $this->render('parts/index.html.twig', [
            'parts' => $partsRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/new", name="parts_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $part = new Parts();
        $form = $this->createForm(PartsType::class, $part);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $part->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($part);
            $entityManager->flush();

            return $this->redirectToRoute('parts_index');
        }

        return $this->render('parts/new.html.twig', [
            'part' => $part,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}", name="parts_show", methods={"GET"})
     */
    public function show(Parts $part): Response
    {
        return $this->render('parts/show.html.twig', [
            'part' => $part,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parts_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parts $part): Response
    {
        $form = $this->createForm(PartsType::class, $part);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parts_index', [
                'id' => $part->getId(),
            ]);
        }

        return $this->render('parts/edit.html.twig', [
            'part' => $part,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}", name="parts_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Parts $part): Response
    {
        if ($this->isCsrfTokenValid('delete'.$part->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($part);
            $entityManager->flush();
        }

        return $this->redirectToRoute('parts_index');
    }


}
