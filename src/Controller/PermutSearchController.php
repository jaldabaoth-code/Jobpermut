<?php

namespace App\Controller;

use LogicException;
use App\Entity\Rome;
use App\Entity\User;
use RuntimeException;
use App\Service\Geocode;
use App\Service\Direction;
use App\Entity\RegisteredUser;
use App\Repository\RegisteredUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PermutSearchController extends AbstractController
{
    /**
     * @Route("permutsearch", name="permutsearch")
     */
    public function index(RegisteredUserRepository $regUserRepo, Direction $direction, Geocode $geocode): Response
    {
        $regUsersDatas = [];
        $tripSummary1 = [];
        $homeCityCoordinate = [];
        $workCityCoordinate = [];
        $userData = [];
        $rome = new Rome();
        $user = new User();

        /** @var User */
        $user = $this->getUser();
        $user = $user->getRegisteredUser();

        if ($user !== null) {
            /** @var RegisteredUser */
            $rome = $user->getRome();
            try {
                $homeCityCoordinate = $geocode->getCoordinates($user->getCity());
                $workCityCoordinate = $geocode->getCoordinates($user->getCityJob());
            } catch (LogicException $e) {
                $exception = $e->getMessage();
                $this->addFlash('geocode', $exception);
            } catch (RuntimeException $e) {
                $exception = $e->getMessage();
                $this->addFlash('geocode', $exception);
            }

            try {
                $tripSummary1 = $direction->tripSummary($homeCityCoordinate, $workCityCoordinate);
                $userData = [
                    'homeCity' => $homeCityCoordinate,
                    'workCity' => $workCityCoordinate,
                    'tripSummary1' => $tripSummary1,
                ];

                $usersByRome = $regUserRepo->findby(['rome' => $rome], [], 5);

                $regUsersDatas = $this->regUsersDatas(
                    $tripSummary1,
                    $workCityCoordinate,
                    $homeCityCoordinate,
                    $usersByRome,
                    $geocode,
                    $direction
                );

                usort($regUsersDatas, function ($first, $last) {
                    return $last['timeGained'] <=> $first['timeGained'];
                });
            } catch (RuntimeException $e) {
                $exception = $e->getMessage();
                $this->addFlash('warning', $exception);
            }
        };

        return $this->render('permutsearch/index.html.twig', [
            'userData' => $userData,
            'regUsersData' => $regUsersDatas
        ]);
    }



    private function regUsersDatas(
        ?array $tripSummary1,
        ?array $workCityCoordinate,
        ?array $homeCityCoordinate,
        array $usersByRome,
        Geocode $geocode,
        Direction $direction
    ): array {
        /** @var User */
        $user = $this->getUser();
        $regUserData = [];
        $regUsersDatas = [];
        $userHomeCoordinates = [];
        $userWorkCoordinates = [];
        $tripSummary2 = [];
        $tripSummary3 = [];
        $tripSummary4 = [];

        foreach ($usersByRome as $regUser) {
            if ($regUser !== $user) {
                $regUserData = $this->regUserData(
                    $regUser,
                    $homeCityCoordinate,
                    $workCityCoordinate,
                    $geocode,
                    $direction
                );
                $userHomeCoordinates = $regUserData['userHomeCoordinates'] ?? [];
                $userWorkCoordinates = $regUserData['userWorkCoordinates'] ?? [];
                $tripSummary2 = $regUserData['tripSummary2'] ?? [];
                $tripSummary3 = $regUserData['tripSummary3'] ?? [];
                $tripSummary4 = $regUserData['tripSummary4'] ?? [];

                $duration1 = 0;
                $duration2 = 0;
                $duration3 = 0;
                $duration4 = 0;

                if ($tripSummary1 && $tripSummary2 && $tripSummary3 && $tripSummary4) {
                    $duration1 = (intval($tripSummary1['duration']['hours']) * 60) +
                        (intval($tripSummary1['duration']['minutes']));
                    $duration2 = (intval($tripSummary2['duration']['hours']) * 60) +
                        (intval($tripSummary2['duration']['minutes']));
                    $duration3 = (intval($tripSummary3['duration']['hours']) * 60) +
                        (intval($tripSummary3['duration']['minutes']));
                    $duration4 = (intval($tripSummary4['duration']['hours']) * 60) +
                        (intval($tripSummary4['duration']['minutes']));
                }

                $timeGained = $duration1 - $duration2;
                $otherTimeGained = $duration3 - $duration4;

                if ($timeGained > 0 && $otherTimeGained > 0) {
                    $regUsersDatas[$regUser->getId()] = [
                        'registeredUser' => $regUser,
                        'userHome' => $userHomeCoordinates,
                        'userWork' => $userWorkCoordinates,
                        'tripSummary2' => $tripSummary2,
                        'timeGained' => $timeGained,
                    ];
                }
            }
        };
        return $regUsersDatas;
    }

    public function regUserData(
        RegisteredUser $regUser,
        ?array $homeCityCoordinate,
        ?array $workCityCoordinate,
        Geocode $geocode,
        Direction $direction
    ): array {
        $regUserData = [];
        $userHomeCoordinates = [];
        $userWorkCoordinates = [];
        $tripSummary2 = [];
        $tripSummary3 = [];
        $tripSummary4 = [];

        try {
            $userHomeCoordinates = $geocode->getCoordinates($regUser->getCity());
            $userWorkCoordinates = $geocode->getCoordinates($regUser->getCityJob());
        } catch (LogicException $e) {
            $exception = $e->getMessage();
            $this->addFlash('geocode', $exception);
        } catch (RuntimeException $e) {
            $exception = $e->getMessage();
            $this->addFlash('geocode', $exception);
        }

        try {
            $tripSummary2 = $direction->tripSummary($homeCityCoordinate, $userWorkCoordinates);
            $tripSummary3 = $direction->tripSummary($userHomeCoordinates, $userWorkCoordinates);
            $tripSummary4 = $direction->tripSummary($userHomeCoordinates, $workCityCoordinate);
            $regUserData = [
                'userHomeCoordinates' => $userHomeCoordinates,
                'userWorkCoordinates' => $userWorkCoordinates,
                'tripSummary2' => $tripSummary2,
                'tripSummary3' => $tripSummary3,
                'tripSummary4' => $tripSummary4,
            ];
        } catch (RuntimeException $e) {
            $exception = $e->getMessage();
            $this->addFlash('warning', $exception);
        }

        return $regUserData;
    }
}
