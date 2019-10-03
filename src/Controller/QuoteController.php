<?php
// src/Controller/QuoteController.php

namespace App\Controller;

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
     * @Route("/task_success", name="task_success")
     */
    public function task_success()
    {
        return $this->render('task_success.html.twig');
    }

    /**
     * @Route("/quotes", name="quotes")
     */
    public function index(Request $request)
    {

        $quotesStore = \SleekDB\SleekDB::store('quotes', __DIR__ . '/../../var/sleekDB');
        $quotes = $quotesStore->fetch();


        $isResponse =false;
        $name = $request->query->get('name');
        if($name != '') {
            $quotess = array_filter($quotes, function ($item) use ($name) {
                if (stripos(strtolower($item['content']), strtolower($name)) !== false) {
                    return true;
                }
                return false;
            });
            if(!empty($quotess)) $isResponse=true;

            $quotes=[];
            if($isResponse) $quotes=$quotess;
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

        $form = $this->createForm(QuoteType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quote = $form->getData();
            $quotesStore = \SleekDB\SleekDB::store('quotes', __DIR__ . '/../../var/sleekDB');

            $quotesStore->insert($quote);

            return $this->redirectToRoute('quotes');
        }


        return $this->render('quotes_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quotes/delete/{id}", name="quotes_delete")
     */
    public function delete($id)
    {
        $quotesStore = \SleekDB\SleekDB::store('quotes', __DIR__ . '/../../var/sleekDB');
        $quotesStore->where( '_id', '=', $id )->delete();


        return $this->redirectToRoute('quotes');

    }

    /**
     * @Route("/quotes/edit/{id}", name="quotes_edit")
     */
    public function edit($id,Request $request)
    {
        $quotesStore = \SleekDB\SleekDB::store('quotes', __DIR__ . '/../../var/sleekDB');
        $quote = $quotesStore->where( '_id', '=', $id )->fetch();


        $form = $this->createForm(QuoteType::class,$quote[0]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quote = $form->getData();
            $quotesStore->where( '_id', '=', $id )->update($quote);

            return $this->redirectToRoute('quotes');
        }


        return $this->render('quotes_edit.html.twig', [
            'form' => $form->createView(),
        ]);



    }

}