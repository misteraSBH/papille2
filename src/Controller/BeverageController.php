<?php

namespace App\Controller;

use App\Entity\Beverage;
use App\Form\BeverageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BeverageController extends AbstractController
{


    /**
     * @Route("/beverage/{id}/edit", name="app_beverage_edit")
     */
    public function edit(int $id, Beverage $beverage, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(BeverageType::class, $beverage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $beverage Beverage */
            $beverage = $form->getData();

            $entityManager->persist($beverage);
            $entityManager->flush();

            return $this->redirectToRoute("app_restaurant_beverages_show",  ["id"=> $beverage->getRestaurant()->getId()]);
        }

        return $this->render('restaurant/add_beverage.html.twig', [
            'form'=>$form->createView(),
            'beverage'=>$beverage,
            'restaurant'=>$beverage->getRestaurant(),
        ]);
    }

    /**
     * @Route("/beverage/{id}/delete", name="app_beverage_delete")
     */
    public function delete(int $id, Beverage $beverage, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($beverage);
        $entityManager->flush();

        return $this->redirectToRoute("app_restaurant_beverages_show",  ["id"=> $beverage->getRestaurant()->getId()]);
    }
}
