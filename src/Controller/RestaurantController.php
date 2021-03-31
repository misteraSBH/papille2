<?php

namespace App\Controller;

use App\Entity\Beverage;
use App\Entity\Dessert;
use App\Entity\Dish;
use App\Entity\MenuRestaurant;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Form\BeverageType;
use App\Form\DessertType;
use App\Form\DishType;
use App\Form\MenuRestaurantType;
use App\Form\RestaurantType;
use App\Repository\MenuRestaurantRepository;
use App\Repository\RestaurantRepository;
use App\Service\ImageUploaderHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestaurantController
 * @IsGranted("ROLE_RESTAURANT_OWNER")
 */

class RestaurantController extends AbstractController
{
    /**
     * @Route("/", name="root")
     */
    public function indexroot(){
        return $this->redirectToRoute("restaurant");
    }


    /**
     * @Route("/restaurant", name="restaurant")
     * @IsGranted("ROLE_SUPER_ADMIN")
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

        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

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

        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        return $this->render("restaurant/show_beverages.html.twig",[
            'restaurant' => $restaurant,
        ]);

    }

    /**
     * @Route("/restaurant/{id}/add_dish", name="app_restaurant_dish_add")
     */
    public function addDish(Request $request, int $id, Restaurant $restaurant, EntityManagerInterface $entityManager):Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

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

        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

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

    /**
     * @Route("/restaurant/{id}/edit", name="app_restaurant_edit")
     */
    public function edit(int $id, Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request, ImageUploaderHelper $fileUploader): Response
    {

        /*if($restaurant->getUser() != $this->getUser()){
            throw new \Exception('Vilain');
        }*/
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            /**@var $restaurant Restaurant */
            $restaurant = $form->getData();

            $uploadedFile = $form->get('picture')->getData();

            if($uploadedFile) {

                $fileName = $fileUploader->upload($uploadedFile);

                $restaurant->setPicture($fileName);
            }

            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_beverages_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit.html.twig', [
            'form'=>$form->createView(),
            'restaurant'=>$restaurant,
        ]);
    }



    /**
     * @Route("/restaurant/{id}/show_dessert", name="app_restaurant_dessert_show")
     */
    public function showDessert(int $id,EntityManagerInterface $entityManager):Response
    {

        $restaurantRepository = $entityManager->getRepository(Restaurant::class);
        $restaurant = $restaurantRepository->find($id);
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        return $this->render("restaurant/show_desserts.html.twig",[
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/add_dessert", name="app_restaurant_dessert_add")
     */
    public function addDessert(int $id, Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $form = $this->createForm(DessertType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $dessert Dessert */
            $dessert = $form->getData();
            $restaurant->addDessert($dessert);

            $entityManager->persist($dessert);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_dessert_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/add_dessert.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/restaurant/{id}/edit_dessert", name="app_restaurant_dessert_edit")
     */
    public function editDessert(int $id, Dessert $dessert, EntityManagerInterface $entityManager, Request $request, RestaurantRepository $restaurantRepository): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $form = $this->createForm(DessertType::class, $dessert);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $dessert Dessert */
            $dessert = $form->getData();

            $entityManager->persist($dessert);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_dessert_show",  ["id"=> $dessert->getRestaurant()->getId()]);
        }

        return $this->render('dish/edit.html.twig', [
            'monFormulaire'=>$form->createView(),
            'dessert'=>$dessert,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/delete_dessert", name="app_restaurant_dessert_delete")
     */
    public function deleteDessert(int $id, Dessert $dessert, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($dessert);
        $entityManager->flush();

        return $this->redirectToRoute("app_restaurant_dessert_show",  ["id"=> $dessert->getRestaurant()->getId()]);
    }

    /**
     * @Route("/restaurant/dashboard", name="app_restaurant_dashboard")
     */
    public function dashboard(): Response
    {
        #$this->getUser()->getRestaurant()->count();
        $restaurants = $this->getUser()->getRestaurant();

        return $this->render('restaurant/dashboard.html.twig', [
            'restaurants'=>$restaurants,
        ]);
    }


    /**
     * @Route("/restaurant/{id}/delete", name="app_restaurant_delete")
     */
    public function delete(int $id, Restaurant $restaurant): Response
    {
        $this->denyAccessUnlessGranted("DELETE_RESTAURANT", $restaurant);

        return new Response("Coucou super admin");
    }

    # -----------------
    # MenuRestaurant
    # -----------------

    /**
     * @Route("/restaurant/{id}/show_menurestaurant", name="app_restaurant_menurestaurant_show")
     */
    public function showMenuRestaurant(int $id, Restaurant $restaurant):Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $restaurant->getMenusrestaurant();
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        return $this->render("restaurant/show_menus_restaurant.html.twig",[
            'restaurant' => $restaurant,
        ]);

    }


    /**
     * @Route("/restaurant/{id}/add_menurestaurant", name="app_restaurant_menurestaurant_add")
     */
    public function addMenuRestaurant(int $id, Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request):Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $menurestaurant = new MenuRestaurant();
        $menurestaurant->setRestaurant($restaurant);

        $form = $this->createForm(MenuRestaurantType::class, $menurestaurant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $menurestaurant MenuRestaurant */
            $menurestaurant = $form->getData();
            $restaurant->addMenusrestaurant($menurestaurant);

            $entityManager->persist($menurestaurant);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_menurestaurant_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/add_menu_restaurant.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/restaurant/{id}/edit_menurestaurant/{idmenurestaurant}", name="app_restaurant_menurestaurant_edit")
     */
    public function editMenuRestaurant(int $id, int $idmenurestaurant,Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request, MenuRestaurantRepository $menuRestaurantRepository): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $menuRestaurant = $menuRestaurantRepository->find($idmenurestaurant);

        $form = $this->createForm(MenuRestaurantType::class, $menuRestaurant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //dd( $form->get('dishes')->getData());
            /**@var $menuRestaurant MenuRestaurant */

            $menuRestaurant = $form->getData();

            $entityManager->persist($menuRestaurant);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_menurestaurant_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit_menu_restaurant.html.twig', [
            'monFormulaire'=>$form->createView(),
            'menuRestaurant'=>$menuRestaurant,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/delete_menurestaurant/{idmenurestaurant}", name="app_restaurant_menurestaurant_delete")
     */
    public function deleteMenuRestaurant(int $id, int $idmenurestaurant, Restaurant $restaurant, EntityManagerInterface $entityManager, MenuRestaurantRepository $menuRestaurantRepository): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $menuRestaurant = $menuRestaurantRepository->find($idmenurestaurant);

        $entityManager->remove($menuRestaurant);
        $entityManager->flush();

        return $this->redirectToRoute("app_restaurant_menurestaurant_show",  ["id"=> $id]);
    }
}

