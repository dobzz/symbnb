<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->findAll();
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * Permet de modifier le commentaire
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     *
     * @return void
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro {$comment->getId()} a bien été modifié"
            );
        }

        return $this->render('admin/comment/edit.html.twig',[
            'comment' => $comment,
            'form' => $form->CreateView()
        ]);
    }

    /**
     * Supprimer un commentaire
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     *
     * @return void
     */
    public function delete(Comment $comment, ObjectManager $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_comment_index');
    }
}
