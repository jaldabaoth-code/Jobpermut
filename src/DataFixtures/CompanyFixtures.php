<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Company;
use Faker\Provider\fr_FR\Address;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SubscriptionFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 5; $i < UserFixtures::MAX_FIXTURES; $i++) {
            $code = $i - 4;
            $company = new Company();
            $company->setName($this->faker->company());
            $company->setAddress($this->faker->address());
            $company->setCode('C0000' . $code);
            $company->addSubscription($this->getReference('subscription_' . $i));

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SubscriptionFixtures::class,
        ];
    }
}
