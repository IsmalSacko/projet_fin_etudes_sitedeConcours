<?php

namespace App\Controller;

use App\Entity\Annonces;

use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param AnnoncesRepository $annoncesRepository
     * @return Response
     */
    public function index(AnnoncesRepository $annoncesRepository)
    {
        $content = 'Binvenue sur "notre site d\'annonces des concours" !';
        return $this->render('home/index.html.twig', [
            'content' =>$content,
            'ads' => $annoncesRepository->findAll(),

        ]);
    }

}
