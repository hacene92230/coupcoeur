<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="annonce_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annonce_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //On récupère les images transmises
             $images = $form->get("images")->getData();
             dd($images); 

             //On boucle sur les images 
             foreach ($images as $image) {
                 //On génère un nouveau nom de fichier 
                 $fichier = md5(uniqid()) . '.' . $image->guessExtension();
 
                 //On copie le fichier dans le dossier upload 
                 $image->move(
                     //Lieu de stockage défini dans service.yml 
                     $this->getParameter("image_directory"),
                     //nom du fichier à déplacer dans le dossier 
                     $fichier
                 );
 
                 //On stocke l'image dans la db (son nom)
                $image = new Images(); 
                $image->setNom($fichier);
                $annonce->addImage($image); 
                 
             }
             //On persiste notre annonce 
             $manager->persist($annonce); 
             $manager->flush();
                       
            
        }
        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_show", methods={"GET"})
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="annonce_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonce $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonce_index');
    }
}
