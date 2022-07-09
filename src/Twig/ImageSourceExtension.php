<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ImageSourceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('src', [$this, 'source']),
        ];
    }

    public function source(string $src): string
    {
        if (preg_match('#/uploads/avatars/http#', $src)) {
            return str_replace('/uploads/avatars/', '', $src);
        }

        return $src;
    }
}
