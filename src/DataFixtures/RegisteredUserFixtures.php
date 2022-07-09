<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\RegisteredUser;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RegisteredUserFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private const ROME = ['H2101', 'G1201', 'K1101'];

    private const USERS_POSTAL_ADRESS = [
        'n0' => [
            'streetNumber' => '4',
            'street' => 'Avenue Pasteur',
            'city' => 'Saran',
            'zipCode' => '	45770'
        ],
        'n1' => [
            'streetNumber' => '10',
            'street' => 'Avenue Pasteur',
            'city' => 'Olivet',
            'zipCode' => '45160'
        ],
        'n2' => [
            'streetNumber' => '1',
            'street' => 'Avenue Pasteur',
            'city' => 'Chécy',
            'zipCode' => '45430'
        ],
        'n3' => [
            'streetNumber' => '9',
            'street' => 'Avenue Pasteur',
            'city' => 'Saint Pryvé Saint Mesmin',
            'zipCode' => '45750'
        ],
        'n4' => [
            'streetNumber' => '11',
            'street' => 'Avenue Pasteur',
            'city' => 'Ingré',
            'zipCode' => '45140'
        ],
        'n5' => [
            'streetNumber' => '2',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Saint Cyr en Val',
            'zipCode' => '45590'
        ],
        'n6' => [
            'streetNumber' => '6',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Orléans',
            'zipCode' => '45000'
        ],
        'n7' => [
            'streetNumber' => '8',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Paris',
            'zipCode' => '75000'
        ],
        'n8' => [
            'streetNumber' => '10',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Mareau-aux-près',
            'zipCode' => '45370'
        ],
        'n9' => [
            'streetNumber' => '4',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Saint Jean de Braye',
            'zipCode' => '45800'
        ],
    ];

    private const USERS_JOB_ADRESS = [
        'n0' => [
            'streetNumber' => '4',
            'street' => 'Avenue Pasteur',
            'city' => 'Olivet',
            'zipCode' => '45160'
        ],
        'n1' => [
            'streetNumber' => '10',
            'street' => 'Avenue Pasteur',
            'city' => 'Saran',
            'zipCode' => '	45770'
        ],
        'n2' => [
            'streetNumber' => '1',
            'street' => 'Avenue Pasteur',
            'city' => 'Saint Pryvé Saint Mesmin',
            'zipCode' => '45750'
        ],
        'n3' => [
            'streetNumber' => '9',
            'street' => 'Avenue Pasteur',
            'city' => 'Chécy',
            'zipCode' => '45430'
        ],
        'n4' => [
            'streetNumber' => '11',
            'street' => 'Avenue Pasteur',
            'city' => 'Saint Cyr en Val',
            'zipCode' => '45590'
        ],
        'n5' => [
            'streetNumber' => '2',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Ingré',
            'zipCode' => '45140'

        ],
        'n6' => [
            'streetNumber' => '6',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Paris',
            'zipCode' => '75000'

        ],
        'n7' => [
            'streetNumber' => '8',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Orléans',
            'zipCode' => '45000'
        ],
        'n8' => [
            'streetNumber' => '10',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Saint Jean de Braye',
            'zipCode' => '45800'
        ],
        'n9' => [
            'streetNumber' => '4',
            'street' => 'Avenue de Bois Preau',
            'city' => 'Mareau-aux-près',
            'zipCode' => '45370'
        ],
    ];


    public const MAX_REALISTIC_FIXTURES = 10;


    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::MAX_REALISTIC_FIXTURES; $i++) {
            $registeredUser = new RegisteredUser();
            $registeredUser->setFirstname($this->faker->firstName());
            $registeredUser->setLastname($this->faker->lastName());
            $registeredUser->setPhone($this->faker->phoneNumber());
            $registeredUser->setStreetNumber(self::USERS_POSTAL_ADRESS['n' . $i]['streetNumber']);
            $registeredUser->setStreet(self::USERS_POSTAL_ADRESS['n' . $i]['street']);
            $registeredUser->setZipcode(self::USERS_POSTAL_ADRESS['n' . $i]['zipCode']);
            $registeredUser->setCity(self::USERS_POSTAL_ADRESS['n' . $i]['city']);
            $registeredUser->setJobStreetNumber(self::USERS_JOB_ADRESS['n' . $i]['streetNumber']);
            $registeredUser->setJobStreet(self::USERS_JOB_ADRESS['n' . $i]['street']);
            $registeredUser->setJobZipcode(self::USERS_JOB_ADRESS['n' . $i]['zipCode']);
            $registeredUser->setCityJob(self::USERS_JOB_ADRESS['n' . $i]['city']);
            $registeredUser->setRome($this->getReference(self::ROME[rand(0, 1)]));
            $registeredUser->setUser($this->getReference('user_' . $i));
            $registeredUser->setSubscription($this->getReference('subscription_' . $i));

            $manager->persist($registeredUser);
        }

        // for User in Demo
        $registeredUser = new RegisteredUser();
        $registeredUser->setFirstname('John');
        $registeredUser->setLastname('Doe');
        $registeredUser->setPhone($this->faker->phoneNumber());
        $registeredUser->setStreetNumber(3);
        $registeredUser->setStreet('rue des Oiseaux');
        $registeredUser->setZipcode(45000);
        $registeredUser->setCity('Orléans');
        $registeredUser->setJobStreetNumber(4);
        $registeredUser->setJobStreet('rue du Pont');
        $registeredUser->setJobZipcode(45640);
        $registeredUser->setCityJob('Sandillon');
        $registeredUser->setRome($this->getReference(self::ROME[rand(0, 1)]));
        $registeredUser->setUser($this->getReference('user_demo'));

        $manager->persist($registeredUser);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SubscriptionFixtures::class,
            RomeFixtures::class,
        ];
    }
}
