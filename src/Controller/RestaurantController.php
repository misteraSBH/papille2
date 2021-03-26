<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $restaurantRepository = $entityManager->getRepository(Restaurant::class);
        $restaurants = $restaurantRepository->findAll();

        return $this->render("restaurant/index.html.twig",[
            'restaurants' => $restaurants,
        ]);
    }
}
