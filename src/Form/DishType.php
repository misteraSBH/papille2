<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\SideDish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    private $currentSideDishes =[];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options["data"])) {
            /** @var SideDish $sidedish */
            $sidedish = $options["data"];
            $this->currentSideDishes = $sidedish->getRestaurant()->getSideDishes();

        }

        $builder
            ->add('name')
            ->add('price')
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'required' => false,
            ])
            ->add('sidedishes', EntityType::class, [
                'label' => "Side Dishes choice",
                'multiple'=>"true",
                'expanded'=>"true",
                'class' => SideDish::class,
                'choices' => $this->currentSideDishes,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }
}
