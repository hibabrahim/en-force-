<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ProduitController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    #[Route('/listProduct', name:'app_Aff')]
     public function listpro(ProduitRepository $authorrepository):Response{
    $product =$authorrepository->findAll();
    return $this->render('produit/index.html.twig',[
        'product'=>$product,
    ]);
}
}
