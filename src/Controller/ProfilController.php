<?php

namespace App\Controller;
use DateTime;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
         
        ]);
    }


    /**
     * @Route("/utilisateur-contact", name="user_contact_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // _________debut code_____
            // je récupére la date du jour 
            $date_jour = new DateTime()  ;
            //je set l'object contact avec la date du jour
            $contact->setDateEnvoi($date_jour);//
            // je récupére le user connécté 
            $user = $this->getUser();
            // je set mon object contact avec le user connécté
            $contact->setUser($user);
            //_________fin code _________
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/new_contact.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }
   /**
     * @Route("/new-adresse", name="user_adresse_new", methods={"GET", "POST"})
     */
    public function newAdresse( AdresseRepository $adresseRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        // Récupération user connecté
        $user = $this->getUser(); 

        if ($form->isSubmitted() && $form->isValid()) {
            // Boucle afin de passer les autres adresses en false et la nouvelle en True
            $adresse -> setUser($user);
            $adresse->setStatut(true);
            $list_adresse = $user->getAdresses();
            foreach($list_adresse as $user_adresse){
                $user_adresse->setStatut(false);
                $entityManager->persist($user_adresse);
                
            
            }
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/new_adresse.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }
}