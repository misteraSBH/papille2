<?php

namespace App\Controller;

use App\Entity\Beverage;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Dish;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $masession): Response
    {

        $cart = new Cart();

        if(!$masession) {
            $masession->start();
            if(!$masession->get("app_current_cart")){
                $masession->set("app_current_cart", $cart);
            }
        }
        $masession->set("app_current_cart", $cart);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route(path="/cart/addProduct/{id}", name="app_cart_cartitem_product_add")
     */
    public function addProduct(int $id, Product $product, SessionInterface $session)
    {
        $cart = $session->get("app_current_cart");

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(1);

        $cart->addCartitem($cartItem);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }


    /**
     * @Route(path="/cart/removeProduct/{id}")
     */
    public function deleteCartItem(int $id, Product $product, SessionInterface $session)
    {
        $cart = $session->get("app_current_cart");

        $cart->removeCartitem($product);
        return new Response('Coucou');

    }

}
