<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager):void
    {
        $this->loadCategory($manager);
        $this->loadWish($manager);
    }

    public function loadCategory(ObjectManager $manager):void
    {
        $categories = ['Travel & Adventure','Entertainment', 'Sport', 'Human Relations', 'Other'];
        foreach ($categories as $cat) {
            $category = new Category();
            $category->setName($cat);

            $manager->persist($category);
        }
        $manager->flush();
    }
    public function loadWish(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->word());
            $wish->setDescription($faker->text());
            $wish->setAuthor($faker->word());
            $wish->setDateCreated($faker->dateTime());
            $wish->setIsPublish($faker->boolean());
            $wish->setCategory($faker->randomElement($categories));

        $manager->persist($wish);
        }
        $manager->flush();
    }
}
