<?php

// src/Controller/HelloWorldController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloWorldController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function index()
    {
        return new Response('<html><body>Hello World!</body></html>');
    }

    /**
     * @Route("/hello/{page}", name="hello_name")
     */
    public function list($page)
    {
//                                  return new Response('<html><body>Hello '.$page.' ! </body></html>');

        return $this->render('helloWorldV2.html.twig', [
            'pnom' => $page,
        ]);
    }
}
