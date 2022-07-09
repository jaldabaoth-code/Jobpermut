<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserFixtures extends Fixture
{
    private const USERS_PASSWORDS = [
        'user' => [
            'password' => '123456789',
            'role' => 'ROLE_USER',
        ],
        'admin' => [
            'password' => 'admin123456789',
            'role' => 'ROLE_ADMIN',
        ],
        'superadmin' => [
            'password' => 'admin123456789',
            'role' => 'ROLE_SUPERADMIN',
        ],
    ];

    public const MAX_FIXTURES = 10;

    private UserPasswordEncoderInterface $passwordEncoder;
    private Generator $faker;
    private HttpClientInterface $client;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, HttpClientInterface $client)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create('fr_FR');
        $this->client = $client;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();

        //for ROLE_USER
        for ($i = 0; $i < self::MAX_FIXTURES; $i++) {
            $response = $this->client->request(
                'GET',
                'https://randomuser.me/api/',
                [
                    'query' => [
                        'nat' => 'fr'
                    ]
                ]
            );
            $fakeUser = $response->toArray();
            $user = new User();
            $user->setEmail($this->faker->unique()->email());
            $user->setUsername($this->faker->unique()->firstName() . $this->faker->randomNumber(2));
            $user->setRoles(self::USERS_PASSWORDS['user']['role']);
            $user->setCreatedAt($this->faker->dateTimeBetween('-2 week', 'now'));
            $user->setAvatar($fakeUser['results'][0]['picture']['large']);
            $user->setIsVisible(true);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    self::USERS_PASSWORDS['user']['password']
                )
            );
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        //for use in demo
        $user = new User();
        $user->setEmail('john@doe.com');
        $user->setUsername('John');
        $user->setRoles(self::USERS_PASSWORDS['user']['role']);
        $user->setCreatedAt($this->faker->dateTimeBetween('-2 week', 'now'));
        $user->setIsVisible(true);
        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword($user, self::USERS_PASSWORDS['user']['password'])
        );
        $manager->persist($user);
        $this->addReference('user_demo', $user);

        //for ROLE_ADMIN
        $user = new User();
        $user->setEmail('wildjobexchangeAdmin@gmail.com');
        $user->setUsername('ADMIN');
        $user->setRoles(self::USERS_PASSWORDS['admin']['role']);
        $user->setCreatedAt($this->faker->dateTimeBetween('-2 week', 'now'));
        $user->setIsVisible(false);
        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword($user, self::USERS_PASSWORDS['admin']['password'])
        );
        $manager->persist($user);

        $user = new User();
        $user->setEmail('wildjobexchangeSuperAdmin@gmail.com');
        $user->setUsername('SUPERADMIN');
        $user->setRoles(self::USERS_PASSWORDS['superadmin']['role']);
        $user->setCreatedAt($this->faker->dateTimeBetween('-2 week', 'now'));
        $user->setIsVisible(false);
        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword($user, self::USERS_PASSWORDS['superadmin']['password'])
        );
        $manager->persist($user);

        $manager->flush();
    }
}
