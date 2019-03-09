<?php

namespace App\Controller;

use App\Entity\Element;
use App\Entity\Entree;
use App\Entity\Produit;
use App\Entity\Sortie;
use App\Form\ProduitType;
use App\Form\SortieType;
use App\Repository\CategorieRepository;
use App\Repository\EntreeRepository;
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

    /**
     * @var $entreeRepository
     */
    private $entreeRepository;

    public function __construct(CategorieRepository $categorieRepository,ProduitRepository $produitRepository,EntreeRepository $entreeRepository, ObjectManager $em)
    {
        $this->categorieRepository = $categorieRepository;
        $this->produitRepository = $produitRepository;
        $this->entreeRepository = $entreeRepository;
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

    //Route entree unique

    /**
     * @Route("/entree/{id}", name="entree.index")
     */
    public function entree($id)
    {
        $produits = $this->produitRepository->find($id);
        $entrees = $this->entreeRepository->findBy([
           'produit' => $id
        ]);

        return $this->render('entree/index.html.twig', [
            'produit' => $produits,
            'entrees' => $entrees
        ]);
    }

    //Route sortie unique

    /**
     * @Route("/sortie/add", name="sortie.add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sortie(Request $request)
    {
        $sortie = new Sortie();
        $elements = new Element();
        $entree = new Entree();

        $sortie->addElement($elements);

        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            foreach ($sortie->getElements() as $element){

                //Récuperatin du quantité à démandé
                $quantiteDemande = $element->getQuantiteSortie();

                /*
                 * Récupération du somme du quantitéEntree à un produit spécifié
                 */
                $sommeStockeProduit = $this->entreeRepository->sumProduit($element->getProduit()->getId());
                $restant = ((int)$sommeStockeProduit['qt'] - $quantiteDemande);

                if($restant < 0){
                    $message = "Stock insuffisant";
                }
                elseif ($restant > 0){
                    $message = "Stock suffisant";


                    /**
                     * @var $entreePremiereLigne Entree
                     * Récupération du première ligne du date le plus récent du quantité d'un produit séléctionnée
                     */
                    $entreePremiereLigne = $this->entreeRepository->recuperationPremierLigne($element->getProduit()->getId(), 0, 1);
                    $quantitePremierLigne = $entreePremiereLigne->getQuantiteEntree();

                    /*
                     * Calcul du reste dans le stock avec la première ligne séléctionné
                     * pour savoir s'il devait récupérer la second ligne et ainsi de suite jusqu'à ce que le $restant soit > 0
                     */
                    $restant2 = $quantitePremierLigne - $quantiteDemande;

                    /*
                     * Update directe du quantiteEntree si la première ligne est suffisant
                     */
                    if($restant > 0) {

                        $entreePremiereLigne->setQuantiteEntree($restant2);

                        $sortie->addElement($element);
                        $this->em->persist($sortie);

                        //Test si la mis à jour du premier ligne marche
                        $this->em->persist($entreePremiereLigne);
                        $this->em->flush();
                    }

                    $i = 0;
                    $j = 1;
                    /*
                     * Quand la première ligne n'est pas suffisant. C'est à dire $restant2 négatif
                     */
                    while ($restant2 < 0){
                        $offset = $i+1;
                        $limit = $j;

                        /**
                         * @var $entreePrecedentLigne Entree
                         * Modification du quantite du ligne précédent par 0
                         */
                        $offsetMoinUn = $offset - 1;
                        $limitMoinUn = $limit;
                        $entreePrecedentLigne = $this->entreeRepository->recuperationPremierLigne($element->getProduit()->getId(), $offsetMoinUn, $limitMoinUn);
                        $modification = $entreePrecedentLigne->setQuantiteEntree(0);
                        //$this->em->persist($modification);
                        //$this->em->flush();

                        /**
                         * @var $quantite2 Entree
                         * On arrete la boucle si $restant est inférieur ou égal à $quantiteLigneSuivant
                         */
                        $quantite2 = $this->entreeRepository->recuperationPremierLigne($element->getProduit()->getId(), $offset, $limit);
                        $quantiteLigneSuivant = $quantite2->getQuantiteEntree();
                        $restant2 = $quantiteLigneSuivant - abs($restant2);
                        $i++;
                    }


                    if($restant2 >= 0){
                        /*
                         * Modification table element et sortie
                         */
                        dump($element->getSortie()->getId());
/*
                        $elementTable = $elements->setProduit($element->getProduit()->getId())
                                                ->setSortie($element->getSortie())
                                                ->setQuantiteSortie($element->getQuantiteSortie())
                        ;

                        $tableElementSortie = $sortie->addElement($elementTable);
                        $this->em->persist($tableElementSortie);
*/
                        /*
                         * Persistance du table entree
                         */
/*
                        $modif = $quantite2->setQuantiteEntree($restant);
                        $this->em->persist($modif);
                        $this->em->flush();
*/
                    }
                }
                else {
                    $message = "Rupture";
                }
                //dump($message);
            }
            //dump($produit);
        }

        return $this->render('sortie/add.html.twig', [
            'form' => $form->createView()
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
