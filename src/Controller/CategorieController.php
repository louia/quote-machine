<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Util\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/topCategorie", name="categorie_top", methods={"GET"})
     */
    public function topCatg()
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $formated = [];
        foreach ($categories as $categorie) {
            $formated[] = [
                'nb' => $categorie->countCitation(),
                'name' => $categorie->getName(),
                'id' => $categorie->getId(),
                'slug' => $categorie->getSlug(),
            ];
        }

        return $this->render('categorie/top.html.twig', [
            'categories' => $formated,
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugger $slugger): Response
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/delete", name="categorie_delete")
     */
    public function delete(Categorie $categorie): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('categorie_index');
    }
}
