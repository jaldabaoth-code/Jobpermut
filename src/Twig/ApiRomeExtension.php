<?php

namespace App\Twig;

use App\Service\ApiRome\ApiRome;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ApiRomeExtension extends AbstractExtension
{
    private ApiRome $apiRome;

    public function __construct(ApiRome $apiRome)
    {
        $this->apiRome = $apiRome;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('rome_name', [$this, 'romeName']),
        ];
    }

    public function romeName(string $codeRome): string
    {
        return $this->apiRome->getJobsByCodeName($codeRome)[0]['libelle'];
    }
}
