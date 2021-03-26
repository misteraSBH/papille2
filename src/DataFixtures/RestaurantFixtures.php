<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));

        for($i=1;$i<=1000;$i++){
            $fakeRestaurant = new Restaurant();

            $fakeRestaurant->setName($faker->company);
            $fakeRestaurant->setAddress($faker->address);
            $fakeRestaurant->setType($faker->sauceName());

            $manager->persist($fakeRestaurant);
            $manager->flush();

        }
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

        $manager->flush();
    }
}
