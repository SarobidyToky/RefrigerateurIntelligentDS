<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    /**
     * @Route("/entree", name="entree.index")
     */
    public function index()
    {
        return $this->render('entree/index.html.twig', [
            'controller_name' => 'EntreeController',
        ]);
    }
}
