<?php

namespace App\Service;

use Exception;
use RuntimeException;
use App\Service\FormatDuration;
use Symfony\Component\HttpClient\HttpClient;

class Direction
{
    private string $key;
    private const STATUS = 200;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getDirection(
        ?array $firstCoordinate,
        ?array $secondCoordinate,
        string $preference = 'recommended'
    ): ?array {
        $coordinates = [];
        $coordinates[] = $firstCoordinate;
        $coordinates[] = $secondCoordinate;

        $client = HttpClient::create();
        $response = $client->request(
            'POST',
            'https://api.openrouteservice.org/v2/directions/driving-car/geojson',
            [
                'headers' => [
                    'Accept' => 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8',
                    'Authorization' => $this->key,
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'json' => [
                    'coordinates' => $coordinates,
                    'preference' => $preference

                ]
            ]
        );

        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === self::STATUS) {
            // associative array (distances and durations)
            $content = $response->toArray()['features'][0];

            return $content;
        }
        throw new RuntimeException('Le service est temporairement indisponible');
    }

    public function tripSummary(?array $firstCoordinate, ?array $secondCoordinate): ?array
    {
        $durationCalc = new FormatDuration();
        $summary = [];
        try {
            $trip = $this->getDirection($firstCoordinate, $secondCoordinate);
            assert($trip !== null);
            if (empty($trip['properties']['summary']['duration'])) {
                $durationToGo = 0;
            } else {
                $durationToGo = $trip['properties']['summary']['duration'];
            }
            $duration = intval(strval(($durationToGo * 2)));
            $duration = $durationCalc->duration($duration);
            if (empty($trip['properties']['summary']['distance'])) {
                $distanceToGo = 0;
            } else {
                $distanceToGo = $trip['properties']['summary']['distance'];
            }
            $distance = number_format((intval(round($distanceToGo * 2) / 1000)), 0, '', ' ');

            $annualDuration = intval(strval(302 * ($durationToGo * 2)));
            $annualDuration = $durationCalc->duration($annualDuration);

            $annualDistance = number_format((302 * (intval(round($distanceToGo * 2) / 1000))), 0, '', ' ');

            $summary = array(
                'duration' => $duration,
                'distance' => $distance,
                'annualDuration' => $annualDuration,
                'annualDistance' => $annualDistance,
            );
        } catch (RuntimeException $e) {
            throw new RuntimeException('Le service est temporairement indisponible');
        }

        return $summary;
    }
}
