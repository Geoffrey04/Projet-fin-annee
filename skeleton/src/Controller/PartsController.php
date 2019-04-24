<?php

namespace App\Controller;

use App\Entity\Parts;
use App\Entity\SearchPartsAuthor;
use App\Entity\SearchPartsGroup;
use App\Entity\SearchPartsStyles;
use App\Entity\SearchPartsTitle;
use App\Entity\SearchPartsType;
use App\Form\PartsType;
use App\Form\SearchPartsAuthorType;
use App\Form\SearchPartsGroupType;
use App\Form\SearchPartsStylesType;
use App\Form\SearchPartsTitleType;
use App\Form\SearchPartsTypeType;
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

    const SHEETMUSIC_DIRECTORY = "img/sheetmusic_directory";
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
            $sheet_music = $form->get('pictures')->getData();
            //dump($sheet_music);

            $nameSheetMusic = $this->generateUniqueFileName();

            $sheet_music->move(self::SHEETMUSIC_DIRECTORY, $nameSheetMusic.'.png' );
            $part->setPictures($sheet_music);

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
     * @Route("/show/{id}", name="parts_show", methods={"GET"})
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
        $partUser = $part->getPictures();
        $part->setPictures('');


        $form = $this->createForm(PartsType::class, $part);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sheet_music = $form->get('pictures')->getData();

            if($_FILES['parts']['name']['pictures'] == "")
            {
                $part->setPictures($partUser);

            }else
            {
                $nameSheetMusic = $this->generateUniqueFileName();
                $sheet_music->move(self::SHEETMUSIC_DIRECTORY, $nameSheetMusic . ".png" );
                $part->setPictures($nameSheetMusic.'.png');
            }



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


    /**
     * @return Response
     * @Route("/partsSearch" , name="parts_search")
     */
    public function PartsSearch(Request $request,PartsRepository $partsRepository) : Response
    {

        $search_t = new SearchPartsTitle() ;
        $search_g = new SearchPartsGroup();
        $search_a = new SearchPartsAuthor();
        $search_s = new SearchPartsStyles();
        $search_type = new SearchPartsType();

        $SearchFormBy_title = $this->createForm(SearchPartsTitleType::class, $search_t);
        $SearchFormBy_title->handleRequest($request);
        $data = $SearchFormBy_title->getData();


        $SearchFormBy_Group = $this->createForm(SearchPartsGroupType::class, $search_g);
        $SearchFormBy_Group->handleRequest($request);
        $data2 = $SearchFormBy_Group->getData();


        $SearchFormBy_Author = $this->createForm(SearchPartsAuthorType::class, $search_a);
        $SearchFormBy_Author->handleRequest($request);
        $data3 = $SearchFormBy_Author->getData();



        $SearchFormBy_Styles = $this->createForm(SearchPartsStylesType::class, $search_s);
        $SearchFormBy_Styles->handleRequest($request);
        $data4 = $SearchFormBy_Styles->getData();


        $SearchFormBy_Type = $this->createForm(SearchPartsTypeType::class, $search_type);
        $SearchFormBy_Type->handleRequest($request);
        $data5 = $SearchFormBy_Type->getData();
       // dump($data5);

        if($SearchFormBy_title->isSubmitted())
        {
            $search_t->setSearchTitle($data->getTitle());
            $parts = $partsRepository->findPartsByTitle($search_t)->getResult();

        }
        elseif($SearchFormBy_Group->isSubmitted())
        {
            $search_g->setSearchGroup($data2->getGroupe());
            $parts = $partsRepository->findPartsByGroup($search_g)->getResult();
        }
        elseif($SearchFormBy_Author->isSubmitted())
        {
            $search_a->setSearchAuthor($data3->getAuthor());

            $parts = $partsRepository->findPartsByAuthor($search_a)->getResult();

        }
        elseif ($SearchFormBy_Styles->isSubmitted())
        {
            $search_s->setSearchStyles($data4->getStyles());
            $parts = $partsRepository->findPartsByStyles($search_s)->getResult();
        }
        elseif($SearchFormBy_Type->isSubmitted())
        {
            $search_type->setSearchType($data5->getType());
            $parts = $partsRepository->findPartsByType($search_type)->getResult();

        }
        else
            {
                $parts = $partsRepository->findAll();
            }





        return $this->render('parts\search.html.twig',
            ['parts'=> $parts,
             'user' => $this->getUser(),
             'form_T'=>$SearchFormBy_title->createView(),
             'form_G'=>$SearchFormBy_Group->createView(),
             'form_A'=>$SearchFormBy_Author->createView(),
             'form_S'=>$SearchFormBy_Styles->createView(),
             'form_Type' =>$SearchFormBy_Type->createView(),
            ]);
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
