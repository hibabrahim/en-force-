<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart")
     */
    public function index(\App\Service\CartService1 $cartService): Response
    {
        $remise = 0;
        $totale = $cartService->getTotal();
        if ($totale > 100) {
            $remise = 10;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
            'remise' => $remise
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add(Request $request, $id, \App\Service\CartService1 $cartService)
    {

        $cartService->add($id);
        $this->addFlash(
            'success',
            'Item has been added !'
        );
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/listproduit", name="produit_list")
     */
    public function list(\App\Repository\ProduitRepository $repository)
    {
        $produit = $repository->findAll();
        return $this->render('produit/index.html.twig', ['produits' => $produit]);

    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, \App\Service\CartService1 $cartService)
    {

        $cartService->remove($id);
        $this->addFlash(
            'success',
            'Item has been removed !'
        );
        return $this->redirectToRoute("cart");
    }
    #[Route('/empty', name: 'cart_empty')]
    public function empty(\App\Service\CartService1 $cartService)
{
    $cartService->clear();
    $this->addFlash(
        'success', // Nom du message flash
        'Panier vidé avec succès !' // Contenu du message flash
    );
    return $this->redirectToRoute('cart');
}
}