<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	private UserPasswordHasherInterface $encoder;

	public function __construct(UserPasswordHasherInterface $encoder)
	{
		$this->encoder = $encoder;
	}
    public function load(ObjectManager $manager): void
    {
		$profiles = ["ADMIN", "CLIENT"];
		// $product = new Product();
		// $manager->persist($product);
		$faker = Factory::create('fr_FR');


		for ($i=0; $i<count($profiles); $i++){
			$profile = new Profile();
			$profile->setLibelle($profiles[$i]);
			$profile->setIsDeleted(false);
			for ($j=0; $j<5 ;$j++){
				$user = new User();
				$user->setnom($faker->name())
					->setPrenom($faker->firstName())
					->setEmail($faker->companyEmail())
					->setPseudo($faker->firstNameMale())
					->setAdresse($faker->address())
					->setTelephone($faker->phoneNumber())
					->setProfile($profile);
				$password = "passer";
				$hashedPassword = $this->encoder->hashPassword(
					$user,
					$password
				);
				$user->setPassword($hashedPassword);
				$manager->persist($user);
			}
			$manager->persist($profile);
		}
        $manager->flush();
    }
}
