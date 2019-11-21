<?php

// src/Controller/QuoteController.php

namespace App\Controller;

use App\Entity\Citation;
use App\Form\QuoteType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexBis()
    {
        return $this->redirectToRoute('quotes');
    }

    /**
     * @Route("/quotes/random", name="randomQuote")
     */
    public function randomQuote()
    {
        $quote = $this->getDoctrine()->getRepository(Citation::class)->getRandomquote()[0];

        return $this->render('random_quotes.html.twig', [
            'quote' => $quote,
        ]);
    }

    /**
     * @Route("/quotes", name="quotes")
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAllWithPaginator($paginator, $request);

        $name = $request->query->get('name');
        if ('' != $name) {
            $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAllbyContent($name, $paginator, $request);
        }

        return $this->render('quotes.html.twig', [
            'pagination' => $quotes, 'query' => $name,
        ]);
    }

    /**
     * @Route("/quotes/new", name="quotes_new")
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $quote = new Citation();
        $quote->setAuthor($user);
        $form = $this->createForm(QuoteType::class, $quote);
        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quote = $form->getData();
            $entityManager->persist($quote);
            $entityManager->flush();

            return $this->redirectToRoute('quotes');
        }

        return $this->render('quotes_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quotes/delete/{id}", name="quotes_delete")
     */
    public function delete(Citation $quote)
    {
        $this->denyAccessUnlessGranted('quotes_delete', $quote);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($quote);
        $entityManager->flush();

        return $this->redirectToRoute('quotes');
    }

    /**
     * @Route("/quotes/edit/{id}", name="quotes_edit")
     */
    public function edit(Citation $quote, Request $request)
    {
        $this->denyAccessUnlessGranted('quotes_edit', $quote);
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quotes');
        }

        return $this->render('quotes_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
