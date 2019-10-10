<?php
// src/Controller/QuoteController.php

namespace App\Controller;

use App\Entity\Citation;
use App\Form\QuoteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/quotes", name="quotes")
     */
    public function index(Request $request)
    {

        $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAll();

        $isResponse =false;
        $name = $request->query->get('name');
        if($name != '') {
            $filterQuotes = array_filter($quotes, function ($item) use ($name) {
                if (stripos(strtolower($item['content']), strtolower($name)) !== false) {
                    return true;
                }
                return false;
            });
            if(!empty($filterQuotes)) $isResponse=true;

            $quotes=[];
            if($isResponse) $quotes=$filterQuotes;
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