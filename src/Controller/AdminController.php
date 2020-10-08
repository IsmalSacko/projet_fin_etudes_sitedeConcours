<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/ad/{page<\d+>?1}", name="admin_ad")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param AnnoncesRepository $annoncesRepository
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    // requirements={"page":"\d+"}=> c'est pour la pagination
    public function adminAd(AnnoncesRepository $annoncesRepository, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Annonces::class)
                    ->getPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' =>$pagination,
            'ad' => $annoncesRepository->findAll(),
        ]);
    }

    /**
     * Permet de modifier une annonce par son slug
     * @Route("/admin/annoncce/{id}/edit", name="admin_ads_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param Annonces $ad
     * @return Response
     */
    public function edit(Annonces $ad, Request $request): Response
    {
        $form = $this->createForm(AnnoncesType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ad);

            $em->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistré !");
        }
        return $this->render('admin/ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annocne
     * @Route("/admin/annonce/{id}/delete", name="admin_ads_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Annonces $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Annonces $ad, EntityManagerInterface $manager): Response
    {
        if (count($ad->getAchats()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annocne <strong>{$ad->getTitle()}</strong>
                 car elle possède déjà un ou plusieurs panier(s)"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->setTitle()}</strong> a bien été supprimé"
            );
        }

        return $this->redirectToRoute('admin_ad');
    }

}
