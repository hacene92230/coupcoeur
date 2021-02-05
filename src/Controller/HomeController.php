<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(AnnonceRepository $repo): Response
    {
        return $this->render('home/index.html.twig', [
            'dernierAnnonce' => $repo->findAll(),
        ]);
    }
}
