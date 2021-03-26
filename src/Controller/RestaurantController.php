<?php

namespace App\Controller;

use App\Entity\Beverage;
use App\Entity\Dish;
use App\Entity\Restaurant;
use App\Form\BeverageType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function index(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $restaurantRepository = $entityManager->getRepository(Restaurant::class);
        #$restaurants = $restaurantRepository->findAll();

        $q = $request->query->get('q');
        $query = $restaurantRepository->getQueryFindAllRestaurantsByName($q);


        return $this->render("restaurant/index.html.twig",[
            'restaurants' => $restaurants,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/show_dishes", name="app_restaurant_dishes_show")
     */
    public function showDishes($id):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurantRepository = $entityManager->getRepository(Restaurant::class);
        $restaurant = $restaurantRepository->find($id);

        return $this->render("restaurant/show_dishes.html.twig",[
            'restaurant' => $restaurant,
        ]);

    }

    /**
     * @Route("/restaurant/{id}/show_beverages", name="app_restaurant_beverages_show")
     */
    public function showBeverages($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurantRepository = $entityManager->getRepository(Restaurant::class);
        $restaurant = $restaurantRepository->find($id);

        return $this->render("restaurant/show_beverages.html.twig",[
            'restaurant' => $restaurant,
        ]);

    }

    /**
     * @Route("/restaurant/{id}/add_beverage", name="app_restaurant_beverage_add")
     */
    public function addBeverage(Request $request, int $id, Restaurant $restaurant, EntityManagerInterface $entityManager):Response
    {

        $beverageForm = $this->createForm(BeverageType::class);
        $beverageForm->handleRequest($request);

        if($beverageForm->isSubmitted() && $beverageForm->isValid()){
            /**
             * @var Beverage $beverage
             */
            $beverage = $beverageForm->getData();
            $restaurant->addBeverage($beverage);

            $entityManager->persist($restaurant);
            $entityManager->flush();
            return $this->redirectToRoute('app_restaurant_beverages_show',["id"=>$id]);

        }

        return $this->render("restaurant/add_beverage.html.twig",[
            'restaurant' => $restaurant,
            'form' => $beverageForm->createView(),
        ]);
    }

}
