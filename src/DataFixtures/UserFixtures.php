<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i=1; $i<=50; $i++){
            $user = new User();
            $user->setEmail("visiteur".$i."@test.com");
            $user->setPassword($this->passwordEncoder->encodePassword($user,"123"));

            /*if($i==3){
                $user->setRoles(["ROLE_SUPER_ADMIN"]);
            }*/
            $manager->persist($user);
        }

        $manager->flush();
    }
}
