<?php

namespace App\DataFixtures;

use App\Entity\Beverage;
use App\Entity\Dessert;
use App\Entity\Dish;
use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RestaurantFixtures extends Fixture
{
    private $dishName;

    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        $faker->addProvider(new \Faker\Provider\Lorem($faker));

        for($i=1;$i<=50;$i++){
            $fakeRestaurant = new Restaurant();

            $fakeRestaurant->setName($faker->company);
            $fakeRestaurant->setAddress($faker->address);
            $fakeRestaurant->setType($faker->sauceName());
            $fakeRestaurant->setOpening(rand(1,3));

            $owner = new User();
            $owner->setEmail("resto".$i."@test.com");
            $owner->setPassword($this->passwordEncoder->encodePassword($owner,"123"));

            $owner->setRoles(["ROLE_RESTAURANT_OWNER"]);
            if($i==3){
                $owner->setRoles(["ROLE_SUPER_ADMIN", "ROLE_RESTAURANT_OWNER"]);
            }
            $fakeRestaurant->setUser($owner);




            $dishNames = [];
            for($j=1;$j<=rand(5,15);$j++){
                $fakeDish = new Dish();

                $this->dishName =$faker->foodName();
                while(in_array($this->dishName, $dishNames)){
                    $this->dishName = $faker->foodName();
                }
                $dishNames[] = $this->dishName;

                $fakeDish->setName($this->dishName);
                $fakeDish->setPrice(rand(8,20));

                $fakeRestaurant->addDish($fakeDish);
            }

            $beverageNames = [];
            for($p=1;$p<=rand(5,20);$p++){
                $fakeBeverage = new Beverage();

                $beverageName = $faker->beverageName();

                while(in_array($beverageName, $beverageNames)){
                    $beverageName = $faker->beverageName();
                }

                $fakeBeverage->setName($beverageName);
                $beverageNames[] = $beverageName;

                $fakeBeverage->setPrice(rand(8,20));

                $fakeRestaurant->addBeverage($fakeBeverage);

            }


            $dessertNames = [];
            for($k=1;$k<=rand(3,5);$k++){
                $fakeDessert = new Dessert();

                $dessertName = $faker->fruitName();

                while(in_array($dessertName, $dessertNames)){
                    $dessertName = $faker->fruitName();
                }

                $fakeDessert->setName($dessertName);
                $dessertNames[] = $dessertName;

                $fakeDessert->setPrice(rand(4,11));
                $fakeDessert->setDescription($faker->text(100));

                $fakeRestaurant->addDessert($fakeDessert);

            }


            $manager->flush();

            $manager->persist($fakeRestaurant);

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
