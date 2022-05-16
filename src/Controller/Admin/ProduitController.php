<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                       // je récupére le fichies passé dans le form
                       $image = $form->get('images')->getdata();
                       // si il y a une image de chargée
                       if ($image) {
                           // je crée un nom unique pour cette image et je remet l'extension
                           $img_file_name = uniqid() . '.' . $image->guessExtension();
                           // enregistrer le fichier dans le dossier image 
                           $image->move($this->getParameter('upload_dir'), $img_file_name);
                           // je set l'object article
                           $produit->setImages($img_file_name);
                       } else {
                           // si $image = null je set l'image par default
                           $produit->setImages('defaultimg.jpg');
                       }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $oldname = $produit -> getImages();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                       // je récupére le fichies passé dans le form
                       $image = $form->get('images')->getdata();
                       // si il y a une image de chargée
                       if ($image) {
                           // je crée un nom unique pour cette image et je remet l'extension
                           $img_file_name = uniqid() . '.' . $image->guessExtension();
                           // enregistrer le fichier dans le dossier image 
                           $image->move($this->getParameter('upload_dir'), $img_file_name);
                           // je set l'object article
                           $produit->setImages($img_file_name);
                       } else {
                           // si $image = null je set l'image par default
                           $produit->setImages($oldname);
                       }
            $produitRepository->add($produit);
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
