<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var ObjectManager
     */
    private $manager;


    public function __construct(CategorieRepository $categorieRepository, ObjectManager $manager)
    {
        $this->categorieRepository = $categorieRepository ;

        $this->manager = $manager;
    }

    /**
     * @Route("/categorie", name="categorie")
     */
    public function index()
    {
        $liste_categorie = $this->categorieRepository->findAll();

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'listeCategorie' => $liste_categorie
        ]);
    }


    /**
     * @Route("/categorie/new", name="categorie.new")
     * @Route("/categorie/{id}/edit", name="categorie.edit")
     */
    public function form(Categorie $categorie = null, Request $request)
    {
        if(!$categorie)
        {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->manager->persist($categorie);
            $this->manager->flush();

            return $this->redirectToRoute("categorie");
        }

        return $this->render('categorie/form.html.twig', ['formCategorie' => $form->createView(), "editMode" => $categorie->getId()!== null]);
    }

    /**
     * @Route("/categorie/{id}/delete", name="categorie.delete")
     */
    public function delete(Request $request, Categorie $categorie)
    {
        if($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))){

            $this->manager->remove($categorie);

            $this->manager->flush();
        }

        return $this->redirectToRoute('categorie');

    }

    /**
     * @Route("/categorie/show/{id}", name="categorie.show")
     */
    public function show(Categorie $categorie){

        return $this->render('categorie/show.html.twig', ['categorie' => $categorie]);

    }
}
