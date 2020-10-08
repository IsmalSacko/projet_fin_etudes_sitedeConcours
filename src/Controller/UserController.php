<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 * @Route("/user")
 */
    class UserController extends AbstractController
    {
        private $passwordEncoder;

        public function __construct(UserPasswordEncoderInterface $passwordEncoder){
         $this->passwordEncoder = $passwordEncoder;
    }

        /**
         * @Route("/{page<\d+>?1}", name="user_index", methods={"GET"})
         * @Security("is_granted('ROLE_ADMIN')")
         * @param UserRepository $userRepository
         * @param $page
         * @param Pagination $pagination
         * @return Response
         */
    public function index(UserRepository $userRepository, $page, Pagination $pagination): Response
    {
        $pagination->setEntityClass(User::class)->setPage($page);
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupère les données du formulaire
            $image = $form->get('picture')->getData();
            //on choisit le nom de l'image avec u identifiant unique et gardant le l'extension
            $url = md5(uniqid()) . '.' . $image->guessExtension();
            //on le déplace dans le dossier passé en paramètre
            $image->move($this->getParameter('users_images_directory'), $url);
            $user->setPicture($url);
            $user->setHash($this->passwordEncoder->encodePassword($user, $user->getHash()));
            $user->setUpdatedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "Votre compte a bien éte créé ! Connectez-vous maintenant");

            return $this->redirectToRoute('account_login');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

        /**
         *
         * @Route("/{slug}/edit", name="user_edit", methods={"GET","DELETE","POST"})
         * @Security("is_granted('ROLE_USER') or user === user.getSlug()",
        message="Ce profil ne vous appartient pas,vous ne pouvez pas le modifier !")
         * @param Request $request
         * @return Response
         */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $user->getPicture();
            $user->setHash($this->passwordEncoder->encodePassword($user, $user->getHash()));
           unlink($this->getParameter('users_images_directory').'/'.$picture);

            $image = $form->get('picture')->getData();
            $url = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('users_images_directory'), $url);
            $user->setPicture($url);

            $user->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Vos modifications ont été mises à jour !");

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $picture = $user->getPicture();
            //Si on efface une image de la base de donnéeé, on l'efface également en local
            unlink($this->getParameter('users_images_directory').'/'.$picture);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_index');
    }


}
