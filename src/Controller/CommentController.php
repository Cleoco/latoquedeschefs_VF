<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController
{
    private $menu_categories;
    function __construct(CategoryRepository $repo)
    {
        $this->menu_categories = $repo->findAll();
    }

    /**
     * @Route("/commentaires", name="comment_index")
     */
    public function index(CommentRepository $commentRepository): Response
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('comment/index.html.twig', [
            
            'comments' => $commentRepository->findAll(),
            'menu_categories'=>$menu_categories 
        ]);
        
    }

    /**
     * @Route("/commentaires/new", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'menu_categories'=> $this->menu_categories
        ]);
    }

    /**
     * @Route("/commentaires/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    
    {
        $menu_categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
            'menu_categories'=> $this->menu_categories
            
        ]);
    }

    /**
     * @Route("/commentaires/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commentaires/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
        
    }
}
