<?php
// src/Controller/QuoteController.php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Citation;
use App\Form\QuoteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
        $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAll();

        if(sizeof($quotes) >=2){
            $quote = $quotes[rand(0,(sizeof($quotes)-1))];
        }

        return $this->render('random_quotes.html.twig', [
            'quote' => $quote
        ]);
    }


    /**
     * @Route("/quotes", name="quotes")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAll();

        $session = new Session();
        if(sizeof($quotes)>=2) $session->set('random', 'true');
        else $session->set('random', 'false');

        $isResponse =false;
        $name = $request->query->get('name');
        if($name != '') {
            $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAllbyContent($name);
            if (isset($quotes) || !empty($quotes)) {
                $isResponse = true;
            }
        }

        return $this->render('quotes.html.twig', [
            'quotes' => $quotes,'result'=>$isResponse,'query'=>$name
        ]);
    }

    /**
     * @Route("/quotes/new", name="quotes_new")
     */
    public function new(Request $request)
    {

        $quote = new Citation();
        $form = $this->createForm(QuoteType::class,$quote);
        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quote = $form->getData();
            dump($quote);
//            $entityManager->persist($quote);
//            $entityManager->flush();

//            return $this->redirectToRoute('quotes');
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

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($quote);
        $entityManager->flush();


        return $this->redirectToRoute('quotes');

    }

    /**
     * @Route("/quotes/edit/{id}", name="quotes_edit")
     */
    public function edit(Citation $quote,Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(QuoteType::class,$quote);

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