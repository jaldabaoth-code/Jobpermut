<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class SubscriptionFixtures extends Fixture
{

    private const CV = [
        'source' => './public/uploads/cv.pdf',
        'destination' => './public/uploads/curriculums/'
    ];

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < UserFixtures::MAX_FIXTURES; $i++) {
            $subscription = new Subscription();
            $subscription->setSubscriptionAt($this->faker->dateTimeBetween('-2 week', 'now'));
            $subscription->setCurriculum('cv' . $i . '.pdf');
            $manager->persist($subscription);
            copy(self::CV['source'], self::CV['destination'] . 'cv' . $i . '.pdf');
            $this->addReference('subscription_' . $i, $subscription);
        }

        $manager->flush();
    }
}
