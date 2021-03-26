<?php

namespace App\DataFixtures;

use App\Entity\Dish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DishFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       /* $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));

        for($i=1;$i<=50;$i++){
            $fakeDish = new Dish();

            $fakeDish->setName($faker->foodName());
            $fakeDish->setPrice(rand(10,20));

            $manager->persist($fakeDish);


        }
        $manager->flush();*/
        // Generator
        /*
         $faker->foodName();      // A random Food Name
        $faker->beverageName();  // A random Beverage Name
        $faker->dairyName();  // A random Dairy Name
        $faker->vegetableName();  // A random Vegetable Name
        $faker->fruitName();  // A random Fruit Name
        $faker->meatName();  // A random Meat Name
        $faker->sauceName();  // A random Sauce Name
        */

    }
}
