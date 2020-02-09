<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Entity\Category;
use App\Form\AdviceType;
use App\Repository\AdviceRepository;
use App\Repository\CategoryRepository;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdviceController extends AbstractController
{


    private $menu_categories;
    function __construct(CategoryRepository $repo)
    {
        $this->menu_categories = $repo->findAll();
    }


    /**
     * @Route("/fiches_pratiques", name="advice_index", methods={"GET"})
     */
    public function index(AdviceRepository $adviceRepository): Response
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('advice/index.html.twig', [
            'advices' => $adviceRepository->findAll(),
            'menu_categories'=>$menu_categories 
        ]);
    }

    /**
     * @Route("/fiches_pratiques/new", name="advice_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $advice = new Advice();
        $form = $this->createForm(AdviceType::class, $advice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($advice);
            $entityManager->flush();

            return $this->redirectToRoute('advice_index');
        }

        return $this->render('advice/new.html.twig', [
            'advice' => $advice,
            'form' => $form->createView(),
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/fiches_pratiques/{id}", name="advice_show", methods={"GET"})
     */
    public function show(Advice $advice): Response
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('advice/show.html.twig', [
            'advice' => $advice,
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/fiches_pratiques/{id}/edit", name="advice_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Advice $advice): Response
    {
        $form = $this->createForm(AdviceType::class, $advice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('advice_index');
        }

        return $this->render('advice/edit.html.twig', [
            'advice' => $advice,
            'form' => $form->createView(),
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/fiches_pratiques/{id}", name="advice_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Advice $advice): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($advice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('advice_index');
    }

    /**
     * @Route("/fiches_pratiques/by/{id}", name="advice_category")
     */
    public function adviceByCategory($id, CategoryRepository $repo){
        $category = $repo->find($id);
        $advices = $category->getAdvices();
        return $this->render('advice/index.html.twig', [
            'advices'=> $advices,
            'menu_categories'=>$this->menu_categories 
        ]);
    }

}
