<?php

namespace App\Service\ApiRome;

use App\Entity\Rome;
use App\Service\ApiRome\ApiRomeJobs;

class ApiRomeAppellations extends ApiRomeJobs
{
    /**
     * return all appelation for one job
     *
     * @param Rome $rome
     * @return array
     */
    public function getAppelationsByJob(Rome $rome): array
    {
        $response = $this->client->request(
            'GET',
            self::URL_REQUEST_GET . 'metier/' . $rome->getCode() . '/appellation',
            [
                'headers' =>  [
                    'Authorization' => $this->getToken()
                ],
            ]
        );

        return $response->toArray();
    }

    /**
     * return all OGR
     *
     * @return array
     */
    public function getAllAppelations(): array
    {
        $response = $this->client->request(
            'GET',
            self::URL_REQUEST_GET . 'appellation',
            [
                'headers' =>  [
                    'Authorization' => $this->getToken()
                ],
            ]
        );

        return $response->toArray();
    }

    /**
     * Return more details of appelation
     *
     * @param string $appelationCode
     * @return array
     */
    public function getDetailsOfAppellation(string $appelationCode): array
    {
        $response = $this->client->request(
            'GET',
            self::URL_REQUEST_GET . 'appellation/' . $appelationCode,
            [
                'headers' =>  [
                    'Authorization' => $this->getToken()
                ],
            ]
        );

        return $response->toArray();
    }
}
