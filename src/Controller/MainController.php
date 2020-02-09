<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $repo = $this->getDoctrine()->getRepository(Recipe::class)->findBy([],['createdAt' => 'desc'],3);
        return $this->render('main/index.html.twig', [
            'recipes' => $repo,
            'menu_categories'=>$menu_categories 
        ]);
        
    }
    
  
  
}
