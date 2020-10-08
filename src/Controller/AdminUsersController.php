<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{

    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_user_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param UserRepository $userRepository
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(UserRepository $userRepository, $page, Pagination $pagination): Response
    {
        $pagination->setEntityClass(User::class)->setPage($page);
        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination,
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Permet de modifier ou éditer un utilisateur
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager) : Response{
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',
                "Les modification de l'utilisateur <strong>n°{$user->getId()}</strong> ont bien été enrzgistrées"
            );
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/user/edit.html.twig',[
            'form' =>$form->createView(),
            'users' =>$user,
        ]);
    }

    /**
     * Permet supprimer un utlitisateur
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager) : Response{

        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success',
            "L'utilisateur <strong>n°{$user->getId()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_user_index');
    }
}
