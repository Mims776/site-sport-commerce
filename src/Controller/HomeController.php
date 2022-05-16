<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategoryRepository;
use App\Repository\CarrouselRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
     /**
     * @Route("/", name="home")
     */
    public function indexx(ProduitRepository $produitRepository,CarrouselRepository $carrouselRepository): Response
    {
        return $this->render('home/index.html.twig', 
        [
           'list_produit'=>$produitRepository->findAll(),
           'carrousels' =>$carrouselRepository->findAll()
        ]);
    }
    
        /**
     * @Route("/basket", name="show_produits_basket")
     */
    public function index_basket(ProduitRepository $produitrepository,CategoryRepository $categoryrepository): Response
    {
        $category = $categoryrepository -> findOneBy(["name"=>"Basket-Ball"]);
        return $this->render('home/basket.html.twig', [
            "liste_produits"=>$produitrepository->findBy(["category"=>$category])
           
        ]);
    }
        /**
     * @Route("/football", name="show_produits_football")
     */
    public function index_football(ProduitRepository $produitrepository,CategoryRepository $categoryrepository): Response
    {
        $category = $categoryrepository -> findOneBy(["name"=>"football"]);
        return $this->render('home/football.html.twig', [
            "liste_produits"=>$produitrepository->findBy(["category"=>$category])
           
        ]);
    }
        /**
     * @Route("/produits", name="show_produits_produits")
     */
    public function index_produits(ProduitRepository $produitrepository,CategoryRepository $categoryrepository): Response
    {
        $category = $categoryrepository -> findOneBy(["name"=>"produits"]);
        return $this->render('home/produits.html.twig', [
            "liste_produits"=>$produitrepository->findBy(["category"=>$category])
           
        ]);
    }
   
 
    }

    
