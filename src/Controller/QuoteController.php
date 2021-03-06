<?php

// src/Controller/QuoteController.php

namespace App\Controller;

use App\Entity\Citation;
use App\Event\UserExpEvent;
use App\Form\QuoteType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
//        foreach ($quotes as $quote) {
//            $quote[] = ['numberofLike' => $quote->countLikes()];
//        }
        $name = $request->query->get('name');
        if ('' != $name) {
            $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAllbyContent($name, $paginator, $request);
        }

        return $this->render('quotes.html.twig', [
            'pagination' => $quotes, 'query' => $name,
        ]);
    }

    /**
     * @Route("/quotes.csv", name="quotes_csv")
     *
     * @return Response
     */
    public function csv(SerializerInterface $serializer)
    {
        $quotes = $this->getDoctrine()->getRepository(Citation::class)->findAllForCsv();

        $encoders = [new CsvEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $quotesSerialized = $serializer->serialize($quotes, 'csv');

        $response = new Response($quotesSerialized);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'quotes.csv'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @Route("/quotes/new", name="quotes_new")
     */
    public function new(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $quote = new Citation();
        $quote->setAuthor($user);
        $form = $this->createForm(QuoteType::class, $quote);
        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newcatg = false;
            foreach ($quote->getCategorie() as $category) {
                $count = $this->getDoctrine()->getRepository(Citation::class)->findIfUseralreadyUseCatg($user->getId(), $category->getId());
                if (0 == $count['numberOfUsage']) {
                    $newcatg = true;
                    $event = new UserExpEvent($quote, $user);
                    $eventDispatcher->dispatch($event, UserExpEvent::POST_IN_CATG);
                }
            }
            if (!$newcatg) {
                $event = new UserExpEvent($quote, $user);
                $eventDispatcher->dispatch($event, UserExpEvent::NEW_QUOTE);
            }

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
