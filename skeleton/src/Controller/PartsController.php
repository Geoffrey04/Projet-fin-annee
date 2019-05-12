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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parts")
 */
class PartsController extends AbstractController
{
        /*create folder for register the score drums set*/
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
     * @Route("/profile/new", name="parts_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        /*Register a new score and add description of this one by form*/
        $part = new Parts();
        $form = $this->createForm(PartsType::class, $part);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $sheet_music = $form->get('pictures')->getData();

            /*Function generateUniqueFileName -> uploaded pictures have unique name in 'sheetmusic' folder*/
            $nameSheetMusic = $this->generateUniqueFileName().'.png';
            $sheet_music->move(self::SHEETMUSIC_DIRECTORY, $nameSheetMusic);
            $part->setPictures($nameSheetMusic);
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
     * @Route("/profile/show/{id}", name="parts_show", methods={"GET"})
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
        /*Edit the score drums set with form*/
        $partUser = $part->getPictures();
        $part->setPictures('');
        $form = $this->createForm(PartsType::class, $part);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sheet_music = $form->get('pictures')->getData();

            if($_FILES['parts']['name']['pictures'] == "") {
                $part->setPictures($partUser);
            }else {
                $nameSheetMusic = $this->generateUniqueFileName();
                $sheet_music->move(self::SHEETMUSIC_DIRECTORY, $nameSheetMusic . ".png" );
                $part->setPictures($nameSheetMusic.'.png');
                  }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parts_index', [
                'id' => $part->getId(),
            ]);
        }
        return $this->render('parts\edit.html.twig', [
            'part' => $part,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }
    /**
     * @Route("/profile/{id}", name="parts_delete", methods={"DELETE"})
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
     * @Route("/profile/partsSearch" , name="parts_search")
     */
    public function PartsSearch(Request $request,PartsRepository $partsRepository , PaginatorInterface $paginator) : Response
    {

        $parts = $paginator->paginate($partsRepository->findAll(),
            $request->query->getInt('page', 1), 6);

        $search_t = new SearchPartsTitle() ;
        $SearchFormBy_title = $this->createForm(SearchPartsTitleType::class, $search_t);
        $SearchFormBy_title->handleRequest($request);
        $data = $SearchFormBy_title->getData();

        if($SearchFormBy_title->isSubmitted())
        {
            if($data->getTitle()) {
                $search_t->setSearchTitle($data->getTitle());
                $parts = $paginator->paginate($partsRepository->findPartsByTitle($search_t)->getResult(),
                    $request->query->getInt('page', 1),6);
            }

        }

        $search_g = new SearchPartsGroup();
        $SearchFormBy_Group = $this->createForm(SearchPartsGroupType::class, $search_g);
        $SearchFormBy_Group->handleRequest($request);
        $data2 = $SearchFormBy_Group->getData();

        if($SearchFormBy_Group->isSubmitted())
        {
            if($data2->getGroupe()) {
                $search_g->setSearchGroup($data2->getGroupe());
                $parts = $paginator->paginate($partsRepository->findPartsByGroup($search_g)->getResult(),
                    $request->query->getInt('page', 1),6);
            }
        }

        $search_a = new SearchPartsAuthor();
        $SearchFormBy_Author = $this->createForm(SearchPartsAuthorType::class, $search_a);
        $SearchFormBy_Author->handleRequest($request);
        $data3 = $SearchFormBy_Author->getData();

        if($SearchFormBy_Author->isSubmitted())
        {
            if($data3->getAuthor()) {
                $search_a->setSearchAuthor($data3->getAuthor());
                $parts = $paginator->paginate($partsRepository->findPartsByAuthor($search_a)->getResult(),
                    $request->query->getInt('page', 1),6);
            }
        }

        $search_s = new SearchPartsStyles();
        $SearchFormBy_Styles = $this->createForm(SearchPartsStylesType::class, $search_s);
        $SearchFormBy_Styles->handleRequest($request);
        $data4 = $SearchFormBy_Styles->getData();

        if ($SearchFormBy_Styles->isSubmitted())
        {
            if($data4->getStyles()) {
                $search_s->setSearchStyles($data4->getStyles());
                $parts = $paginator->paginate($partsRepository->findPartsByStyles($search_s)->getResult(),
                    $request->query->getInt('page', 1),6);
            }
        }

        $search_type = new SearchPartsType();
        $SearchFormBy_Type = $this->createForm(SearchPartsTypeType::class, $search_type);
        $SearchFormBy_Type->handleRequest($request);
        $data5 = $SearchFormBy_Type->getData();


        if($SearchFormBy_Type->isSubmitted())
        {
            if($data5->getType()) {
                $search_type->setSearchType($data5->getType());
                $parts = $paginator->paginate($partsRepository->findPartsByType($search_type)->getResult(),
                    $request->query->getInt('page', 1),6);
            }

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