<?php

namespace App\Controller;

use RuntimeException;
use App\Service\Direction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/leaflet", name="leaflet_")
 */
class LeafletController extends AbstractController
{
    /**
    * @Route("/direction/{firstStart}/{firstEnd}/{secondStart}/{secondEnd}", name="direction")
    */
    public function direction(
        float $firstStart,
        float $firstEnd,
        float $secondStart,
        float $secondEnd,
        Direction $direction
    ): Response {
        $firstCoordinate = [];
        $secondCoordinate = [];
        $firstCoordinate[] = $firstStart;
        $firstCoordinate[] = $firstEnd;
        $secondCoordinate[] = $secondStart;
        $secondCoordinate[] = $secondEnd;
        $path = [];

        try {
            $path = $direction->getDirection($firstCoordinate, $secondCoordinate);
        } catch (RuntimeException $e) {
            $exception = $e->getMessage();
            $this->addFlash('warning', $exception);
        }

        return $this->json($path);
    }
}
