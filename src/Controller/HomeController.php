<?php

namespace App\Controller;

use App\Entity\Annonces;

use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * @Route ("/contact", name="contact_index")
     * @return Response
     */
    function contact(Request $request) :Response{
      
        
        $to      = 'ismalsacko@gmail.com';
        $subject = $request->get('sujet');
        $message = $request->get('message');

        $headers = array(
            'From' => $request->get('email'),
            'Reply-To' => $request->get('email'),
            'Nom' => $request->get('nom')
            
        );

mail($to, $subject, $message, $headers);

        return  $this->render('home/contact.html.twig');

    }

}
