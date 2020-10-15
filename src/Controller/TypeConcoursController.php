<?php

namespace App\Controller;

use App\Entity\TypeConcours;
use App\Form\TypeConcoursType;
use App\Repository\TypeConcoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/concours")
 */
class TypeConcoursController extends AbstractController
{
    /**
     * @Route("/", name="type_concours_index", methods={"GET"})
     */
    public function index(TypeConcoursRepository $typeConcoursRepository): Response
    {
        return $this->render('type_concours/index.html.twig', [
            'type_concours' => $typeConcoursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_concours_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeConcour = new TypeConcours();
        $form = $this->createForm(TypeConcoursType::class, $typeConcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeConcour);
            $entityManager->flush();

            return $this->redirectToRoute('type_concours_index');
        }

        return $this->render('type_concours/new.html.twig', [
            'type_concour' => $typeConcour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_concours_show", methods={"GET"})
     */
    public function show(TypeConcours $typeConcour): Response
    {
        return $this->render('type_concours/show.html.twig', [
            'type_concour' => $typeConcour,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_concours_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeConcours $typeConcour): Response
    {
        $form = $this->createForm(TypeConcoursType::class, $typeConcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_concours_index');
        }

        return $this->render('type_concours/edit.html.twig', [
            'type_concour' => $typeConcour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_concours_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeConcours $typeConcour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeConcour->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeConcour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_concours_index');
    }
}
