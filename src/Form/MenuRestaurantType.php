<?php

namespace App\Form;

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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options["data"])) {
            /** @var MenuRestaurant $menuRestaurant */
            $menuRestaurant = $options["data"];
            $this->currentDishes = $menuRestaurant->getRestaurant()->getDishes();
        }
      //  dd( $menuRestaurant );
        $builder
            ->add('name')
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MenuRestaurant::class,
        ]);
    }
}
