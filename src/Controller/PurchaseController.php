<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\OrderSlip;
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

        $arrayListOwnerId = [];


        foreach($cart->getCartitems() as $cartItem){

            $idRestaurant = $cartItem->getProduct()->getRestaurant()->getId();
            if(!in_array($idRestaurant, $arrayListOwnerId)){
                $orderSlips[$idRestaurant] = new OrderSlip();
                $arrayListOwnerId[] = $idRestaurant;
            }

            $purchaseItem = new PurchaseItem();
            $purchaseItem->setRefProduct( $cartItem->getProduct());
            $purchaseItem->setName( $cartItem->getProduct()->getName());
            $purchaseItem->setPrice( $cartItem->getProduct()->getPrice());
            $purchaseItem->setQuantity( $cartItem->getQuantity());

            $purchase->addPurchaseItem($purchaseItem);

            $orderSlips[$idRestaurant]->addPurchaseItem($purchaseItem);
            $orderSlips[$idRestaurant]->setPurchase($purchase);
            $orderSlips[$idRestaurant]->setRestaurant($cartItem->getProduct()->getRestaurant());

            $entityManager->persist($orderSlips[$idRestaurant]);

        }


        $purchase->setIdPurchase(date('Ymd').'#'.$purchase->getId());
        #dd($purchase);

        $entityManager->persist($purchase);
        $entityManager->flush();

        $session->clear();
        $entityManager->remove($cart);

        $entityManager->flush();

        $response = new Response("Votre commande a bien ??t?? cr????");
        $response->headers->clearCookie("app_delifood_cart");

        return $response;
    }
}
