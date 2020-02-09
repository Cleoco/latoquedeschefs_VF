<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RecipeController extends AbstractController
{

    private $menu_categories;
    function __construct(CategoryRepository $repo)
    {
        $this->menu_categories = $repo->findAll();
    }


    /**
     * @Route("/recettes", name="recipe_index", methods={"GET"})
     */
    public function index()
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $repo = $this->getDoctrine()->getRepository(Recipe::class);
        $recipes = $repo->findAll();
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'menu_categories'=>$menu_categories 
        ]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
           
            'form' => $form->createView(),
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_show", methods={"GET"})
     */
    public function show(Recipe $recipe): Response
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recipe $recipe): Response
    {   
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //je récupère la date d'aujourd'hui comme date de modification 
             $now = new \DateTime('now', new DateTimeZone('Europe/Paris'));
             $recipe-> setUpdatedAt($now);
            $this->getDoctrine()->getManager()->flush();
             //préparer un message en session limitée
             $this->addFlash('success','La recette a été bien modifiée');
             // redirection 
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
            //préparer un message en session limitée
            $this->addFlash('danger','La recette a été bien supprimée');
        }

        return $this->redirectToRoute('recipe_index');
    }

    /**
     * @Route("/recettes/{id}", name="recettes_category")
     */
    public function recipeByCategory($id, CategoryRepository $repo){
        $category = $repo->find($id);
        $recipes = $category->getRecipes();
        return $this->render('recipe/index.html.twig', [
            'recipes'=> $recipes,
            'menu_categories'=>$this->menu_categories 
        ]);
    }


}
