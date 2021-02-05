<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Bien;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\TypeAnnonce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("FR-fr");

        //Création des users
        for ($i = 0; $i < 50; $i++) {
            $_user[] = new User();
            $_user[$i]->setUsername($faker->firstName)
                ->setPhone("0612211190")
                ->setPassword($this->encoder->encodePassword($_user[$i], 'password'))
                ->setCreatedAt(new \DateTime());
            if ($i == 0) {
                $_user[0]->setRoles(["ROLE_ADMIN"]);
                $_user[0]->setUsername("hacene");
            } else if ($i > 0) {
                $_user[$i]->setRoles(["ROLE_CLIENT"])
                    ->setUsername("user$i");
            }
            $manager->persist($_user[$i]);
        }

        //création des biens
        for ($l = 0; $l < 100; $l++) {
            $_bien[] = new Bien();
            $_bien[$l]->setCreatedAt(new \DateTime())
                ->setChauffage("électrique")
                ->setSurface($faker->numberBetween(10, 200))
                ->setPieces($faker->numberBetween(1, 5))
                ->setChambres($faker->numberBetween(1, 2))
                ->setEtages($faker->numberBetween(1, 20))
                ->setPrix($faker->numberBetween(500, 2000))
                ->setProprietaire($_user[rand(0, count($_user) - 1)])
                ->setAdresse($faker->address)
                ->setVille($faker->city)
                ->setCodePostal($faker->numberBetween(10000, 98000));
            $disponible = [true, false];
            $_bien[$l]->setDisponible(rand(0, count($disponible)))
                ->setNom($faker->words(3, true));
            $manager->persist($_bien[$l]);
        }

        //création des annonces
        for ($i = 0; $i < 30; $i++) {
            $_annonce[] = new Annonce();
            $_annonce[$i]->setTitre($faker->words(3, true))
                ->setBien($_bien[$i]);
            if ($i <= 15) {
                $_annonce[$i]->setType("vente");
            } else {
                $_annonce[$i]->setType("location");
            }
            $_annonce[$i]->setTexte($faker->sentences(3, true));
            $manager->persist($_annonce[$i]);
        }
        $manager->flush();
    }
}
