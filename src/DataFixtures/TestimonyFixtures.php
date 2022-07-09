<?php

namespace App\DataFixtures;

use App\Entity\Testimony;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TestimonyFixtures extends Fixture
{

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 5; $i < UserFixtures::MAX_FIXTURES; $i++) {
            $testimony = new Testimony();
            $testimony->setCommentary($this->faker->realText(150, 2));
            $testimony->setCreatedAt($this->faker->dateTimeBetween('-2 week', 'now'));
            $testimony->setUser($this->getReference('user_' . $i));
            $manager->persist($testimony);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
