<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase", name="app_purchase")
     */
    public function index(SessionInterface $session, CartRepository $cartRepository): Response
    {
        if( $session->get("app_current_cart") == null){
            dd("Panier inconnu");
        } else {
            /**
             * @var Cart $cart
             */
            $cart = $cartRepository->find($session->get("app_current_cart")->getId());
        }

        return $this->render('front/purchase_payment_selection.html.twig', [
            'cart' => $cart,
        ]);
    }


    /**
     * @Route("/purchase/add", name="app_purchase_add")
     */
    public function add(SessionInterface $session, CartRepository $cartRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        if( $session->get("app_current_cart") == null){
            dd("Panier inconnu");
        } else {
            /**
             * @var Cart $cart
             */
            $cart = $cartRepository->find($session->get("app_current_cart")->getId());
        }



        $purchase = new Purchase();
        $purchase->setIdPurchase(date('Ymd').'#');
        $purchase->setAmount( $cart->getTotalAmount());
        $purchase->setCreatedAt(new \DateTime());
        $purchase->setPaymentMethod($request->request->get("paymentMethod"));

        $entityManager->persist($purchase);
        $entityManager->flush();
        /**
         * @var CartItem $cartItem
         */
        foreach($cart->getCartitems() as $cartItem){
            dd($cartItem->getProduct()->getRestaurant());
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setRefProduct( $cartItem->getProduct());
            $purchaseItem->setName( $cartItem->getProduct()->getName());
            $purchaseItem->setPrice( $cartItem->getProduct()->getPrice());
            $purchaseItem->setQuantity( $cartItem->getQuantity());

            $purchase->addPurchaseItem($purchaseItem);

        }

        $purchase->setIdPurchase(date('Ymd').'#'.$purchase->getId());

        $entityManager->persist($purchase);
        $entityManager->flush();

        $session->clear();
        $entityManager->remove($cart);

        $entityManager->flush();

        return new Response("Votre commande a bien été créé");
    }
}
