<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentaireController extends AbstractController
{
    /**
     * @Route("/admin/commentaire/{page<\d+>?1}", name="admin_commentaire_index")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param CommentRepository $repo
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(CommentRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Comment::class)->setPage($page);
        return $this->render('admin/commentaire/index.html.twig', [
            'pagination' => $pagination,
            'comments' =>$repo->findAll()
        ]);
    }

    /**
     * Permat de modifier un commentaire par laiisé par utilisateur
     * @Route("/admin/commentaire/{id}/edit", name="admin_comment_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public  function edit(Comment $comment, Request $request, EntityManagerInterface $manager) : Response{
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success',
                "Les modifications du commentaires <strong>{$comment->getAd()}</strong> ont bien été enregistré !"
            );
           return $this->redirectToRoute('admin_commentaire_index');
        }
        return $this->render('admin/commentaire/edit.html.twig',[
            'form' =>$form->createView(),
            'comments' => $comment,
        ]);
    }

    /**
     * Permet de supprimer un commentaire laissé par un utilisateur
     * @Route("/admin/commentaire/{id}/delete", name="admin_comment_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager) :Response{
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success',
            "Le commentaires <strong>{$comment->getAd()}</strong> a bien été suppprimé !"
        );
        return $this->redirectToRoute('admin_commentaire_index');
    }
}
