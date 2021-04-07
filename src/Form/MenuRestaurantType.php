<?php

namespace App\Form;

use App\Entity\Beverage;
use App\Entity\Dessert;
use App\Entity\Dish;
use App\Entity\MenuRestaurant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MenuRestaurantType extends AbstractType
{
    private $currentDishes =[];
    private $currentBeverages =[];
    private $currentDesserts =[];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options["data"])) {
            /** @var MenuRestaurant $menuRestaurant */
            $menuRestaurant = $options["data"];
            $this->currentDishes = $menuRestaurant->getRestaurant()->getDishes();
            $this->currentBeverages = $menuRestaurant->getRestaurant()->getBeverages();
            $this->currentDesserts = $menuRestaurant->getRestaurant()->getDesserts();
        }
      //  dd( $menuRestaurant );
        $builder
            ->add('name')
            ->add('visible')
            ->add('dishes', EntityType::class, [
                'label' => "Dishes choice",
                'multiple'=>"true",
                 'expanded'=>"true",
                 'class' => Dish::class,

                 'choices' => $this->currentDishes,

               /*'query_builder' => function(EntityRepository $er) {
                    return
                    $er ->createQueryBuilder('d')
                        ->andWhere('d.restaurant = :val')
                        ->setParameter('val',  $this->currentRestaurant)
                        ;
                }*/

            ])
            ->add('beverages', EntityType::class, [
                'label' => "Beverages choice",
                'multiple'=>"true",
                'expanded'=>"true",
                'class' => Beverage::class,
                'choices' => $this->currentBeverages,
            ])
            ->add('desserts', EntityType::class, [
                'label' => "Desserts choice",
                'multiple'=>"true",
                'expanded'=>"true",
                'class' => Dessert::class,
                'choices' => $this->currentDesserts,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuRestaurant::class,
        ]);
    }
}
