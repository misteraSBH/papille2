<?php

namespace App\Controller;

use App\Entity\Beverage;
use App\Entity\Dish;
use App\Entity\Restaurant;
use App\Form\BeverageType;
use App\Form\DishType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $restaurantRepository = $entityManager->getRepository(Restaurant::class);
        #$restaurants = $restaurantRepository->findAll();

        $qname = $request->query->get('name');
        $qaddress = $request->query->get('address');
        $qnbresult = $request->query->get('nbresult');
        #$restaurants = $restaurantRepository->findAllRestaurantsByName($qname, $qaddress);
        $query = $restaurantRepository->getQueryFindAllRestaurantsByName($qname, $qaddress);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $qnbresult /*limit per page*/
        );

        $tab_show_results = [5,10,20,50,100];

        return $this->render("restaurant/index.html.twig",[
            'restaurants' => $pagination,
            'nb_results_affiches' => $qnbresult,
            'tab_show_results' => $tab_show_results,
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
     * @Route("/restaurant/{id}/add_dish", name="app_restaurant_dish_add")
     */
    public function addDish(Request $request, int $id, Restaurant $restaurant, EntityManagerInterface $entityManager):Response
    {

        $dishForm = $this->createForm(DishType::class);
        $dishForm->handleRequest($request);

        if($dishForm->isSubmitted() && $dishForm->isValid()){
            /**
             * @var Dish $dish
             */
            $dish = $dishForm->getData();
            $restaurant->addDish($dish);

            $entityManager->persist($restaurant);
            $entityManager->flush();
            return $this->redirectToRoute('app_restaurant_dishes_show',["id"=>$id]);

        }

        return $this->render("restaurant/add_dish.html.twig",[
            'restaurant' => $restaurant,
            'form' => $dishForm->createView(),
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
