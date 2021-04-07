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
        $qopening = $request->query->get('opening');
        if($qopening == 3){$qopening = "%";}

        $restaurants = $restaurantRepository->findAllRestaurantsByNameorType($qsearch, $qopening);

        $opening_label[1] = "Lunch";
        $opening_label[2] = "Dinner";
        $opening_label[3] = "Lunch & Dinner";

        return $this->render('front/index.html.twig', [
            'restaurants' => $restaurants,
            'opening_label' => $opening_label,
        ]);
    }
}
