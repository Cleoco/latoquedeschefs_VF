<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    
    
    public function load(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        // liste pour mon sélecteur de catégories
        $categories = ['Tartes','Cake','Entremets','Petits gâteaux','Crèmes','À tartiner','Recettes traditionnelles','Chocolats & Confiseries','Boulangerie','À l\'assiette','Pâtisserie du monde'];
        // tableau pour enregistrer chaque objet de type Category
        $tabObjetsCategory = []; 
        // Boucle pour créer autant d'objets que de catégories dans la liste
        foreach($categories as $cat){
            $category = new Category();
            $category->setName($cat);
            $entityManager->persist($category);
            $entityManager->flush();
        }
        return new Response('Saved new product with id '.$category->getId());
        
        
    }
    
    
    
}