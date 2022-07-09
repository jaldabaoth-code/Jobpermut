<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MatchByLikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match", name="match")
     */
    public function index(MatchByLikeRepository $matchRepo): Response
    {
        /** @var User */
        $user = $this->getUser();
        $matchs = $matchRepo->findByUser($user);

        return $this->render('match/index.html.twig', [
            'matchs' => $matchs,
        ]);
    }
}
