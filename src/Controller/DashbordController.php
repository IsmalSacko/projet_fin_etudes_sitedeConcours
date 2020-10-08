<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashbordController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashbord_index")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function index(EntityManagerInterface $manager)
    {
        $users = $manager->createQuery("SELECT count(u) FROM App\Entity\User u")->getSingleScalarResult();
        $ads = $manager->createQuery("SELECT count(a) FROM App\Entity\Annonces a")->getSingleScalarResult();
        $paniers = $manager->createQuery("SELECT count(p) FROM App\Entity\Achat p")->getSingleScalarResult();
        $comments = $manager->createQuery("SELECT count(c) FROM App\Entity\Comment c")->getSingleScalarResult();

        return $this->render('admin/dashbord/index.html.twig', [
            'stats' => compact( 'users','ads' ,'paniers','comments'  )

        ]);
    }
}
