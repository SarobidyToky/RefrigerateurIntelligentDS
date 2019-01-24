<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @var $categorieRepository
     */
    private $categorieRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var $produitRepository
     */
    private $produitRepository;

    public function __construct(CategorieRepository $categorieRepository,ProduitRepository $produitRepository, ObjectManager $em)
    {
        $this->categorieRepository = $categorieRepository;
        $this->produitRepository = $produitRepository;
        $this->em = $em;
    }

    /**
     * @Route("/produit", name="produit.index")
     */
    public function index()
    {
        $categorie = $this->categorieRepository->findAll();


        $produits = $this->produitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'categories' => $categorie,
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/produit/new", name="produit.new")
     */
    public function new(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($produit);
            $this->em->flush();
            return $this->redirectToRoute('produit.index');
        }

        return $this->render('produit/new.html.twig', [
           'produit' =>$produit,
            'form' => $form->createView()
        ]);

    }


}
