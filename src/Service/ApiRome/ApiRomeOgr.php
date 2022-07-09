<?php

namespace App\Service\ApiRome;

use App\Service\ApiRome\ApiRomeAppellations;

class ApiRomeOgr extends ApiRomeAppellations
{

    /**
     * Return full information of one appellation (OGR)
     *
     * @param integer $appellation
     * @return array
     */
    public function getOgr(int $appellation): array
    {
        $response = $this->client->request(
            'GET',
            self::URL_REQUEST_GET . 'appellation/' . $appellation,
            [
                'headers' =>  [
                    'Authorization' => $this->getToken()
                ],
            ]
        );

        return $response->toArray();
    }
}
