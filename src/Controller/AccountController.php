<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\PasswordUpdate;
use App\Form\AccountType;
use App\Form\AnnoncesType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils) : Response
    {
       $error = $utils->getLastAuthenticationError();
       $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig',[
            'error' =>$error !== null,
            'username' => $username,
        ]);
    }

    /**
     * Deconnexion
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout() {

    }

    /**
     * Modification du profile utilisateur
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request) :Response {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $user->getPicture();
            unlink($this->getParameter('users_images_directory').'/'.$picture);
            $image = $form->get('picture')->getData();
            $url = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('users_images_directory'), $url);
            $user->setPicture($url);

            $user->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Vos modifications ont été mises à jour !");

            return $this->redirectToRoute('home');
        }
        return $this->render('account/profile.html.twig',[
            'form' =>$form->createView(),
        ]);
    }

    /**
     * @Route("/account/update-Password",name="profole_password")
     *@IsGranted("ROLE_USER")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword( Request $request,EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder):Response{
        $passwodUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form= $this->createForm(PasswordUpdateType::class, $passwodUpdate);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()){
            if (!password_verify($passwodUpdate->getOldpassword(), $user->getHash())){
                $form-$this->get('oldPassword')->addError(new FormError("Le mot de passe qie vous avez tapé n'est pas votre mot de passe actuelle"));
            }else{
                $newPass = $passwodUpdate->getNewpassword();
                $hash = $encoder->encodePassword($user, $newPass);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                //$this->getDoctrine()->getManager()->flush();
                $this->addFlash("success", "Votre mot de passe a été modifié avec succès !");
                return $this->redirectToRoute('home');
            }
       }
        return $this->render('account/password.html.twig', [
           'form' => $form->createView()
       ]);
    }

}
