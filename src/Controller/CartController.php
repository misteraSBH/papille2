<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(SessionInterface $masession, EntityManagerInterface $entityManager, CartRepository $cartRepository): Response
    {

        #dd($masession);
        if(!$masession->get("app_current_cart")){

            $cart = new Cart();
            $entityManager->persist($cart);
            $entityManager->flush();
            $masession->set("app_current_cart", $cart);
        } else {
            $cart = $cartRepository->find($masession->get("app_current_cart"));
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route(path="/cart/{id}/addProduct/{idproduct}", name="app_cart_cartitem_product_add")
     * @Route(path="/front/cart/{id}/addProduct/{idproduct}", name="app_front_cart_cartitem_product_add")
     */
    public function addProduct(int $id, int $idproduct, Cart $cart, SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Request $request)
    {
        #$cart = $session->get("app_current_cart");
        #dd($cart);


        $product = $productRepository->find($idproduct);

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(1);
        $cartItem->setCart($cart);

        $cart->addCartitem($cartItem);

        $entityManager->persist($cart);
        $entityManager->flush();

        $session->set("app_current_cart", $cart);

        $origine = $request->headers->get('referer');

        return $this->redirect($origine);
    }


    /**
     * @Route(path="/cart/{id}/removeCartItem/{idCartItem}", name="app_cart_cartitem_product_delete")
     */
    public function deleteCartItem(int $id, int $idCartItem, Cart $cart, SessionInterface $session, CartItemRepository $cartItemRepository, EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Request $request, CartRepository $cartRepository)
    {

        $cartItem = $cartItemRepository->find($idCartItem);
        $entityManager->remove($cartItem);
        $entityManager->flush();

        $cart = $cartRepository->find($id);

        $session->set("app_current_cart", $cart );
        $origine = $request->headers->get('referer');

        return $this->redirect($origine);

    }

}
