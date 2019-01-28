<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use App\Repository\EntreeRepository;
use App\Repository\ProduitRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntreeController extends AbstractController
{
    /**
     * @var ProduitRepository
     */
    private $produitRepository;

    /**
     * @var EntreeRepository
     */
    private $entreeRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProduitRepository $produitRepository, EntreeRepository $entreeRepository, ObjectManager $em)
    {
        $this->produitRepository = $produitRepository;
        $this->entreeRepository = $entreeRepository;
        $this->em = $em;
    }

    /**
     * @Route("/entree/new/{id}", name="entree.new")
     */
    public function new(Request $request, Produit $produit)
    {
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entree->setProduit($produit);
            $this->em->persist($entree);
            $this->em->flush();
            return $this->redirectToRoute('entree.index', [
                "id" => $produit->getId()
            ]);
        }

        return $this->render('entree/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
/*
    /**
     * @Route("/entree", name="entree.index")
     */
/*
    public function index()
    {
        $entree = $this->entreeRepository->findAll();

        return $this->render('entree/index.html.twig', [
            'controller_name' => 'EntreeController',
            'entrees' => $entree
        ]);
    }
*/
}
