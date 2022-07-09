<?php

namespace App\Service\ApiRome;

use App\Service\ApiRome\ApiRomeToken;

class ApiRomeJobs extends ApiRomeToken
{
    /**
     * Return list of all jobs
     *
     * @return array
     */
    public function getAllJobs(): array
    {
        $response = $this->client->request(
            'GET',
            self::URL_REQUEST_GET . 'metier',
            [
                'headers' =>  [
                    'Authorization' => $this->getToken()
                ],
            ]
        );

        return $response->toArray();
    }

    /**
     * return job by codeName
     *
     * @return array
     */
    public function getJobsByCodeName(string $code): array
    {
        $response = $this->client->request(
            'GET',
            self::URL_REQUEST_GET . 'metier',
            [
                'headers' =>  [
                    'Authorization' => $this->getToken()
                ],
                'query' => [
                    'code' => $code
                ]
            ]
        );

        return $response->toArray();
    }
}
