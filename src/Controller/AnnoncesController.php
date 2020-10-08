<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Image;
use App\Entity\User;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonce")
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="annonces_index", methods={"GET"})
     * @param AnnoncesRepository $annoncesRepository
     * @return Response
     */
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('annonces/index.html.twig', [
            'ads' => $annoncesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $ads = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $ads);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {

            //REception et traimment de l'image de couverture de notre annonce
            $coverImage = $form->get('image')->getData();
            $myFile = md5(uniqid()) .'.' .$coverImage->guessExtension();
            $coverImage->move(
                $this->getParameter('images_directory'), $myFile);
            $ads->setImage($myFile);


            //on récupère les images uploadées
            $images = $form->get('adImages', 'description')->getData();
            //On boucle sur les images
            foreach ($images as $image) {
                //on génère un nouveau nom de fichier
                //ici on chosi un nom aléatoire pour les + l'extention de celui-ci
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //On copie les fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'), $fichier
                );
                //On stocke le nom de l'image dans la bdd
                $img = new Image();
                $img->setUrl($fichier);
                $ima = $form->get('description')->getData();
                $img->setDescription($ima);
                $ads->addImage($img);

            }
            $ads->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ads);
            $entityManager->flush();

            return $this->redirectToRoute('annonces_index', [
                'slug' =>$ads->getAuthor()
            ]);
        }

        return $this->render('annonces/new.html.twig', [
            'ads' => $ads,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="annonces_show", methods={"GET"})
     * @param Annonces $annonce
     * @return Response
     */
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonces/show.html.twig', [
            'user' =>$this->getUser(),
            'ads' => $annonce,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="annonces_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user === annonce.getAuthor() ",
      message="Cette annonce ne vous appartient,vous ne pouvez pas la modifier !")
     * @param Request $request
     * @param Annonces $annonce
     * @return Response
     */
    public function edit(Request $request, Annonces $annonce): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //REception et traimment de l'image de couverture de notre annonce
            $coverImage = $form->get('image')->getData();
            $myFile = md5(uniqid()) .'.' .$coverImage->guessExtension();
            $coverImage->move(
                $this->getParameter('images_directory'), $myFile);
            $annonce->setImage($myFile);
            //on récupère les images uploadées
            $images = $form->get('adImages')->getData();
            //On boucle sur les images
            foreach ($images as $image) {
                //on génère un nouveau nom de fichier
                //ici on chosi un nom aléatoire pour les + l'extention de celui-ci
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //On copie les fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'), $fichier
                );
                //On stocke le nom de l'image dans la bdd
                $img = new Image();
                $img->setUrl($fichier);
                $ima = $form->get('description')->getData();
                $img->setDescription($ima);
                $annonce->addImage($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index');
        }

        return $this->render('annonces/edit.html.twig', [
            'ads' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_delete", methods={"DELETE"})
     * @param Request $request
     * @param Annonces $annonce
     * @return Response
     */
    public function delete(Request $request, Annonces $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {

               //unlink($this->getParameter('images_directory').'/'.$nom);y

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index');
    }

//    /**
//     * @Route("/supprime/image/{id}", name="annonces_delete_image", methods={"DELETE"})
//     */
//    public function deleteImage(Image $image, Request $request){
//        $data = json_decode($request->getContent(), true);
//
//        // On vérifie si le token est valide
//        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
//            // On récupère le nom de l'image
//            $nom = $image->getUrl();
//            // On supprime le fichier
//            unlink($this->getParameter('images_directory').'/'.$nom);
//
//            // On supprime l'entrée de la base
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($image);
//            $em->flush();
//
//            // On répond en json
//            return new JsonResponse(['success' => 1]);
//        }else{
//            return new JsonResponse(['error' => 'Token Invalide'], 400);
//        }
//    }


}
