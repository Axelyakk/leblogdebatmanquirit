<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        // on instancie le Faker en langue française
        $faker = Faker\Factory::create('fr_FR');

        // Création d'un compte admin
        $admin = new User();

        $admin
            ->setEmail('non536620@gmail.com')
            ->setRegistrationDate( $faker->dateTimeBetween('1-year', 'now'))
            ->setPseudonym('Yakkunai')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Axel181924.')
            )
        ;

        $manager->persist($admin);

        // Création de 10 comptes utilisateurs

        for($i = 0; $i < 10; $i++){

            $user = new User();

            $user
                ->setEmail($faker->email)
                ->setRegistrationDate( $faker->dateTimeBetween('1-year', 'now' ))
                ->setPseudonym($faker->userName)
                ->setPassword(
                    $this->encoder->hashPassword($admin, 'aaaaaaaaA7/')
                )
            ;

            $manager->persist($user);

        }

        for($i = 0; $i < 50; $i++){

            $article = new Article();

            $article
                ->setTitle( $faker->sentence(10))
                ->setContent($faker->paragraph(15))
                ->setPublicationDate( $faker->dateTimeBetween('-1 year', 'now'))
                ->setAuthor($admin)
                ;

            $manager->persist( $article);

        }


        $manager->flush();
    }
}