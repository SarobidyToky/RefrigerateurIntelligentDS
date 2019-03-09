<?php

namespace App\Controller;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     * @var $elementRepository
     */
    private $elementRepository;

    public function __construct(ElementRepository $elementRepository)
    {
        $this->elementRepository = $elementRepository;
    }

    /**
     * @Route("/sortie", name="sortie.index")
     */
    public function index()
    {
        $element = $this->elementRepository->findAll();

        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
            'elements' => $element
        ]);
    }
}
