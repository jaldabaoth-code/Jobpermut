<?php

namespace App\DataFixtures;

use App\Entity\Rome;
use App\Service\ApiRome\ApiRome;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RomeFixtures extends Fixture
{
    private const ROME_FIELDS = ['code', 'libelle'];
    private ApiRome $apiRome;

    public function __construct(ApiRome $apiRome)
    {
        $this->apiRome = $apiRome;
    }
    public function load(ObjectManager $manager)
    {
        $dataRomes = $this->apiRome->getAllJobs();
        foreach ($dataRomes as $dataRome) {
            $rome = new Rome();
            $rome->setCode($dataRome[self::ROME_FIELDS[0]]);
            $rome->setName($dataRome[self::ROME_FIELDS[1]]);
            $manager->persist($rome);
            $this->addReference($rome->getCode(), $rome);
        }
        $manager->flush();
    }
}
