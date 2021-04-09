<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\MenuRestaurant;
use App\Entity\Restaurant;
use App\Repository\CartRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="app_front")
     */
    public function index(EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository, Request $request, SessionInterface $session, CartRepository $cartRepository): Response
    {
        $qsearch = $request->query->get('search');
        $qopening = $request->query->get('opening');
        if($qopening == 3){$qopening = "%";}

        $restaurants = $restaurantRepository->findAllRestaurantsByNameorType($qsearch, $qopening);

        $opening_label[1] = "Lunch";
        $opening_label[2] = "Dinner";
        $opening_label[3] = "Lunch & Dinner";


        if( $session->get("app_current_cart") == null){
            $cart = new Cart();
            $entityManager->persist($cart);
            $entityManager->flush();
        } else {
            $cart = $cartRepository->find($session->get("app_current_cart")->getId());
        }
        $session->set("app_current_cart", $cart);



        return $this->render('front/index.html.twig', [
            'restaurants' => $restaurants,
            'opening_label' => $opening_label,
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/front/menu/{id}", name="app_front_menu_show")
     */

    public function showMenuRestaurant(int $id, Restaurant $restaurant, SessionInterface $session, CartRepository $cartRepository, EntityManagerInterface $entityManager, Request $request)
    {



        if( $session->get("app_current_cart") == null){

            if($request->cookies->get('app_delifood_cart')){
                $cart = $cartRepository->findOneBy(["uuid"=>$request->cookies->get('app_delifood_cart')]);
                $session->set("app_current_cart", $cart);
            } else {

                $cart = new Cart();
                $entityManager->persist($cart);
                $entityManager->flush();

                $cookie = Cookie::create("app_delifood_cart")
                    ->withValue($cart->getUuid())
                    ->withExpires(strtotime('Fri, 20-May-2022 15:00:00 GMT'))
                    ->withSecure(true);
                $session->set("app_current_cart", $cart);
            }

        } else {
            $cart = $cartRepository->find($session->get("app_current_cart")->getId());
            $session->set("app_current_cart", $cart);
        }


        $menus = $restaurant->getMenusrestaurant();

        $response = $this->render('front/show_menus_restaurant.html.twig', [
            'menus' => $menus,
            'cart' => $cart,
        ]);

        if(isset($cookie)) {
            $response->headers->setCookie($cookie);
        }
        return $response;
    }
}
