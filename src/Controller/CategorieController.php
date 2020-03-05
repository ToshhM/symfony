<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie; //On importe l'élément
use App\Form\CategorieType; 
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(Request $request)
    {
        //Récupère Doctrine (service de gestion de BDD)
        $pdo = $this->getDoctrine()->getManager();

        $categorie=new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        //Analyse la requête HTTP
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $pdo->persist($categorie); //prepare
            $pdo->flush();            //execute
        }

        //Récupère tous les produits 
        $categories = $pdo->getRepository(Categorie::class)->findAll();


        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'form_categorie_new'=>$form->createView()
        ]);
    }

    /**
    * @Route("/categorie/{id}", name="ma_categorie")
    */

    public function categorie(Request $request, Categorie $categorie){

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($categorie);
                $pdo->flush();
            }

        return $this->render('categorie/categorie.html.twig',[
            'categorie'=>$categorie,
            'form' => $form->createView()
        ]);

    }
}
