<?php

namespace App\Service\ApiRome;

final class ApiRome extends ApiRomeOgr
{
    private const INDEX = ['libelle', 'code'];

    /**
     * Sort Api response by name
     *
     * @param array $responses
     * @return array
     */
    public function sortResponseByName(array $responses): array
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
        );

        $sort = [];
        foreach ($responses as $response) {
            $response[self::INDEX[0]] = preg_replace(
                array_keys($utf8),
                array_values($utf8),
                $response[self::INDEX[0]]
            );
            $sort[$response[self::INDEX[0]]] = $response[self::INDEX[1]];
        }

        ksort($sort);

        return $sort;
    }
}
