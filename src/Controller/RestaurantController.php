<?php

namespace App\Controller;

use App\Entity\Beverage;
use App\Entity\Dessert;
use App\Entity\Dish;
use App\Entity\MenuRestaurant;
use App\Entity\Restaurant;
use App\Entity\SideDish;
use App\Entity\User;
use App\Form\BeverageType;
use App\Form\DessertType;
use App\Form\DishType;
use App\Form\MenuRestaurantType;
use App\Form\RestaurantType;
use App\Form\SideDishType;
use App\Repository\BeverageRepository;
use App\Repository\DessertRepository;
use App\Repository\DishRepository;
use App\Repository\MenuRestaurantRepository;
use App\Repository\RestaurantRepository;
use App\Repository\SideDishRepository;
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

    # -----------------
    # Restaurant
    # -----------------


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

            $this->addFlash('success', 'Le restaurant a été modifié');

            return $this->redirectToRoute("app_restaurant_edit",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit.html.twig', [
            'form'=>$form->createView(),
            'restaurant'=>$restaurant,
        ]);
    }

    /**
     * @Route("/restaurant/dashboard", name="app_restaurant_dashboard")
     */
    public function dashboard(): Response
    {
        #$this->getUser()->getRestaurant()->count();
        $restaurants = $this->getUser()->getRestaurants();

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
        $this->addFlash('success', 'Le Restaurant a été supprimé');
        return new Response("Coucou super admin");
    }

    # -----------------
    # Dish
    # -----------------

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

            $this->addFlash('success', 'Le plat a été créé');

            return $this->redirectToRoute('app_restaurant_dishes_show',["id"=>$id]);

        }

        return $this->render("restaurant/add_dish.html.twig",[
            'restaurant' => $restaurant,
            'form' => $dishForm->createView(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}/editdish/{iddish}", name="app_restaurant_dish_edit")
     */
    public function editDish(int $id, int $iddish, Restaurant $restaurant, DishRepository $dishRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $dish = $dishRepository->find($iddish);

        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $dish Dish */
            $dish = $form->getData();

            $entityManager->persist($dish);
            $entityManager->flush();

            $this->addFlash('success', 'Le Dish a bien été modifié');
            return $this->redirectToRoute("app_restaurant_dishes_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit_dish.html.twig', [
            'monFormulaire'=>$form->createView(),
            'dish'=>$dish,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/deletedish/{iddish}", name="app_restaurant_dish_delete")
     */
    public function deleteDish(int $id, int $iddish, Restaurant $restaurant, DishRepository $dishRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $dish = $dishRepository->find($iddish);

        $entityManager->remove($dish);
        $entityManager->flush();
        $this->addFlash('success', 'Le Dish a bien été supprimé');
        return $this->redirectToRoute("app_restaurant_dishes_show",  ["id"=> $id]);
    }



    # -----------------
    # Beverages
    # -----------------

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

            $this->addFlash('success', 'Le Beverage a bien été ajouté');
            return $this->redirectToRoute('app_restaurant_beverages_show',["id"=>$id]);

        }

        return $this->render("restaurant/add_beverage.html.twig",[
            'restaurant' => $restaurant,
            'form' => $beverageForm->createView(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}/edit_beverage/{idbeverage}", name="app_restaurant_beverage_edit")
     */
    public function editBeverage(int $id, int $idbeverage, Restaurant $restaurant, BeverageRepository $beverageRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $beverage = $beverageRepository->find($idbeverage);

        $form = $this->createForm(BeverageType::class, $beverage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $beverage Beverage */
            $beverage = $form->getData();

            $entityManager->persist($beverage);
            $entityManager->flush();
            $this->addFlash('success', 'Le Beverage a bien été édité');
            return $this->redirectToRoute("app_restaurant_beverages_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit_beverage.html.twig', [
            'form'=>$form->createView(),
            'beverage'=>$beverage,
            'restaurant'=>$beverage->getRestaurant(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}/delete_beverage/{idbeverage}", name="app_restaurant_beverage_delete")
     */
    public function deleteBeverage(int $id, int $idbeverage, Restaurant $restaurant, BeverageRepository $beverageRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $beverage = $beverageRepository->find($idbeverage);

        $entityManager->remove($beverage);
        $entityManager->flush();
        $this->addFlash('success', 'Le Beverage a bien été supprimé');
        return $this->redirectToRoute("app_restaurant_beverages_show",  ["id"=> $id]);
    }


    # -----------------
    # Desserts
    # -----------------


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

            $this->addFlash('success', 'Le Dessert a bien été ajouté');
            return $this->redirectToRoute("app_restaurant_dessert_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/add_dessert.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/restaurant/{id}/edit_dessert/{iddessert}", name="app_restaurant_dessert_edit")
     */
    public function editDessert(int $id, int $iddessert, Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request, DessertRepository $dessertRepository): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $dessert = $dessertRepository->find($iddessert);
        $form = $this->createForm(DessertType::class, $dessert);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $dessert Dessert */
            $dessert = $form->getData();

            $entityManager->persist($dessert);
            $entityManager->flush();
            $this->addFlash('success', 'Le Dessert a bien été modifié');
            return $this->redirectToRoute("app_restaurant_dessert_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit_dessert.html.twig', [
            'monFormulaire'=>$form->createView(),
            'dessert'=>$dessert,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/delete_dessert/{iddessert}", name="app_restaurant_dessert_delete")
     */
    public function deleteDessert(int $id, int $iddessert,Restaurant $restaurant, EntityManagerInterface $entityManager,DessertRepository $dessertRepository): Response
    {
        $dessert = $dessertRepository->find($iddessert);

        $entityManager->remove($dessert);
        $entityManager->flush();
        $this->addFlash('success', 'Le Dessert a bien été supprimé');
        return $this->redirectToRoute("app_restaurant_dessert_show",  ["id"=> $id]);
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

            $this->addFlash('success', 'Le Menu a bien été ajouté');
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
            $this->addFlash('success', 'Le Menu a bien été modifié');
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
        $this->addFlash('success', 'Le Menu a bien été supprimé');
        return $this->redirectToRoute("app_restaurant_menurestaurant_show",  ["id"=> $id]);
    }




    # -----------------
    # SideDish
    # -----------------

    /**
     * @Route("/restaurant/{id}/show_sidedishes", name="app_restaurant_sidedishes_show")
     */
    public function showSideDish(int $id, Restaurant $restaurant):Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $restaurant->getSideDishes();

        return $this->render("restaurant/show_sidedishes.html.twig",[
            'restaurant' => $restaurant,
        ]);

    }

    /**
     * @Route("/restaurant/{id}/add_sidedish", name="app_restaurant_sidedish_add")
     */
    public function addSideDish(int $id, Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request):Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $sidedish = new SideDish();
        $sidedish->setRestaurant($restaurant);

        $form = $this->createForm(SideDishType::class, $sidedish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $sidedish SideDish */
            $sidedish = $form->getData();
            $restaurant->addSideDish($sidedish);

            $entityManager->persist($sidedish);
            $entityManager->flush();

            $this->addFlash('success', 'Le SideDish a bien été créé');
            return $this->redirectToRoute("app_restaurant_sidedishes_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/add_sidedish.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/restaurant/{id}/edit_sidedish/{idsidedish}", name="app_restaurant_sidedish_edit")
     */
    public function editSideDish(int $id, int $idsidedish,Restaurant $restaurant, EntityManagerInterface $entityManager, Request $request, SideDishRepository $sideDishRepository): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $sideDish = $sideDishRepository->find($idsidedish);

        $form = $this->createForm(SideDishType::class, $sideDish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $sideDish SideDish */

            $sideDish = $form->getData();

            $entityManager->persist($sideDish);
            $entityManager->flush();

            $this->addFlash('success', 'Le SideDish a bien été modifié');
            return $this->redirectToRoute("app_restaurant_sidedishes_show",  ["id"=> $id]);
        }

        return $this->render('restaurant/edit_sidedish.html.twig', [
            'form'=>$form->createView(),
            'sidedish'=>$sideDish,
        ]);
    }

    /**
     * @Route("/restaurant/{id}/delete_sidedish/{idsidedish}", name="app_restaurant_sidedish_delete")
     */
    public function deleteSideDish(int $id, int $idsidedish, Restaurant $restaurant, EntityManagerInterface $entityManager, SideDishRepository $sideDishRepository): Response
    {
        $this->denyAccessUnlessGranted("EDIT_RESTAURANT", $restaurant);

        $sideDish = $sideDishRepository->find($idsidedish);

        $entityManager->remove($sideDish);
        $entityManager->flush();
        $this->addFlash('success', 'Le SideDish a bien été supprimé');
        return $this->redirectToRoute("app_restaurant_sidedishes_show",  ["id"=> $id]);
    }
}

