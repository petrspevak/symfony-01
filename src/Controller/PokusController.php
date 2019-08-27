<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PokusController extends AbstractController
{
    /**
     * @Route("/", name="pokus")
     */
    public function index()
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'PokusController',
        ]);
    }
}
