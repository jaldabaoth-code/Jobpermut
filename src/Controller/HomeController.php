<?php

namespace App\Controller;

use App\Entity\Testimony;
use LogicException;
use RuntimeException;
use App\Service\Geocode;
use App\Entity\VisitorTrip;
use App\Form\VisitorTripType;
use App\Repository\TestimonyRepository;
use App\Service\Direction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"POST", "GET"})
     */
    public function index(
        Request $request,
        Geocode $geocode,
        SessionInterface $session,
        Direction $direction,
        TestimonyRepository $testimonyRepository
    ): Response {
        $visitorTrip = new VisitorTrip();
        $form = $this->createForm(VisitorTripType::class, $visitorTrip);
        $form->handleRequest($request);

        $homeCityCoordinate = [0, 0];
        $workCityCoordinate = [0, 0];
        $tripSummary = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $homeCity = $visitorTrip->getHomeCity();
            $workCity = $visitorTrip->getWorkCity();
            try {
                $homeCityCoordinate = $geocode->getCoordinates($homeCity);
                $workCityCoordinate = $geocode->getCoordinates($workCity);

                $tripSummary = $direction->tripSummary($homeCityCoordinate, $workCityCoordinate);
                return $this->redirectToRoute('home', [
                    '_fragment' => 'map',
                    'homeLong' => $homeCityCoordinate[0] ?? 0,
                    'homeLat' => $homeCityCoordinate[1] ?? 0,
                    'workLong' => $workCityCoordinate[0] ?? 0,
                    'workLat' => $workCityCoordinate[1] ?? 0,
                    'tripSummary' => $tripSummary,
                ]);
            } catch (LogicException $e) {
                $exception = $e->getMessage();
                $this->addFlash('geocode', $exception);
            } catch (RuntimeException $e) {
                $exception = $e->getMessage();
                $this->addFlash('geocode', $exception);
            }

            return $this->redirectToRoute('home', [
                '_fragment' => 'map',
            ]);
        }

        $testimonies = $testimonyRepository->findAll();
        shuffle($testimonies);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'testimonies' => $testimonies,
        ]);
    }
}
