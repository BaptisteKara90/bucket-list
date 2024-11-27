<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->word());
            $wish->setDescription($faker->text());
            $wish->setAuthor($faker->word());
            $wish->setDateCreated($faker->dateTime());
            $wish->setPublish($faker->boolean());

        $manager->persist($wish);
        }
        $manager->flush();
    }
}
