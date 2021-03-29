<?php

namespace App\Controller;


use App\Entity\Dish;
use App\Form\DishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DishController extends AbstractController
{


    /**
     * @Route("/dish/{id}/edit", name="app_dish_edit")
     */
    public function edit(int $id, Dish $dish, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $dish Dish */
            $dish = $form->getData();

            $entityManager->persist($dish);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_dishes_show",  ["id"=> $dish->getRestaurant()->getId()]);
        }

        return $this->render('dish/edit.html.twig', [
            'monFormulaire'=>$form->createView(),
            'dish'=>$dish,
        ]);
    }

    /**
     * @Route("/dish/{id}/delete", name="app_dish_delete")
     */
    public function delete(int $id, Dish $dish, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($dish);
        $entityManager->flush();

        return $this->redirectToRoute("app_restaurant_dishes_show",  ["id"=> $dish->getRestaurant()->getId()]);
    }
}
