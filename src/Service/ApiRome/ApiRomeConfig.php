<?php

namespace App\Service\ApiRome;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ApiRomeConfig
{
    protected const URL_TOKEN = 'https://entreprise.pole-emploi.fr/';
    protected const URL_REQUEST_GET = 'https://api.emploi-store.fr/partenaire/rome/v1/';

    protected HttpClientInterface $client;
    protected string $clientId;
    protected string $secretKey;

    public function __construct(HttpClientInterface $client, string $romeClientId, string $romeSecretKey)
    {
        $this->client = $client;
        $this->clientId = $romeClientId;
        $this->secretKey = $romeSecretKey;
    }

    /**
     * Get the value of client
     */
    protected function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    /**
     * Get the value of romeClientId
     */
    protected function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Get the value of romeSecretKey
     */
    protected function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
