<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="front")
     */
    public function index(EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository, Request $request): Response
    {
        $qsearch = $request->query->get('search');
        $restaurants = $restaurantRepository->findAllRestaurantsByNameorType($qsearch);


        return $this->render('front/index.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }
}
