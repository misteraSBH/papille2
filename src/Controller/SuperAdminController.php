<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class SuperAdminController
 * @package App\Controller
 * @IsGranted ("ROLE_SUPER_ADMIN")
 */

class SuperAdminController extends AbstractController
{
    /**
     * @Route("/superadmin", name="super_admin")
     */
    public function index(): Response
    {
        return $this->render('super_admin/index.html.twig', [
            'controller_name' => 'SuperAdminController',
        ]);
    }


    /**
     * @Route("/superadmin/category", name="app_superadmin_category_list")
     */
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([],["orderNumber"=>"ASC"]);
        #$categories = $categoryRepository->findByParentNull();

        return $this->render('super_admin/list_category.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/superadmin/category/add", name="app_superadmin_category_add")
     */
    public function addCategory(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository):Response
    {
        $category = new Category();
        $category->setParent(null);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){
            /**
             * @var Category $category
             */
            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'The category has been created');
            return $this->redirectToRoute('app_superadmin_category_list');
        }

        return $this->render("super_admin/add_category.html.twig",[
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/superadmin/category/{id}/edit", name="app_superadmin_category_edit")
     */
    public function editCategory(int $id, Category $category, Request $request,  EntityManagerInterface $entityManager, CategoryRepository $categoryRepository):Response
    {
        #$category->children = $categoryRepository->findByParent($id);
        #dd($category);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){
            /**
             * @var Category $category
             */
            $category = $form->getData();
            dump($category);

            $categoryChildren = $categoryRepository->findByParent($category->getId());
            foreach($categoryChildren as $value){
                dump($value);
                $category->addChild($value);
            }

            dd($category);

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'The category '.$category->getName().' has been modified');

            return $this->redirectToRoute('app_superadmin_category_edit', ["id"=>$id]);
        }

        return $this->render("super_admin/edit_category.html.twig",[
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/superadmin/category/{id}/delete", name="app_superadmin_category_delete")
     */
    public function deleteCategory(int $id, Category $category, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'The category '.$category->getName().' has been removed');

        return $this->redirectToRoute("app_superadmin_category_list");
    }

    /**
     * @Route("/superadmin/orderslist", name="app_superadmin_orders_list")
     */
    public function listOrders(EntityManagerInterface $entityManager, PurchaseRepository $purchaseRepository){

        $purchases = $purchaseRepository->findAll();
       // dd($purchases);
        return $this->render("super_admin/list_orders.html.twig",[
            'purchases' => $purchases,
        ]);


    }

}
