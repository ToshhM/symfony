<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit; //On importe l'élément
use App\Form\ProduitType; 
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {   
        //Récupère Doctrine (service de gestion de BDD)
        $pdo = $this->getDoctrine()->getManager();
        //Il est responsable de l'enregistrement des objets et de leur récupération dans la base de données.

        
        /*
            ->findOneBy(['id' =>2])
            ->findBy(['nom'=>'Nom du produit'])
        */
        $produit=new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        
        //Analyse la requête HTTP
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $pdo->persist($produit); //prepare et enregistre le produit 
            $pdo->flush();            //execute celui-ci
        }

        //Récupère tous les produits 
        $produits = $pdo->getRepository(Produit::class)->findAll();


        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form_produit_new'=>$form->createView()
            // "=>" Pour un tableau associatif 
            //"->" Pour un objet
        ]);
    }
}
// "::"accéder aux membres static ou constant, ainsi qu'aux propriétés ou méthodes surchargées d'une classe.