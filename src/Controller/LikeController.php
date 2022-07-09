<?php

namespace App\Controller;

use App\Entity\UserLike;
use App\Entity\User;
use App\Entity\MatchByLike;
use App\Repository\MatchByLikeRepository;
use App\Repository\UserLikeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @Route("/{user}/like", name="like")
     */
    public function switchLike(
        User $user,
        UserLikeRepository $userLikeRepository,
        EntityManagerInterface $entityManager,
        MatchByLikeRepository $matchRepo
    ): Response {
        $heart = false;

        /** @var User */
        $userLiker = $this->getUser();

        $match = false;

        $userLike = $userLikeRepository->findOneBy([
            'userLiker' => $userLiker,
            'userLiked' => $user
        ]);

        if ($userLike == null) {
            $userLike = new UserLike();
            $userLike->setUserLiker($userLiker);
            $userLike->setUserLiked($user);
            $entityManager->persist($userLike);
            /** @var User */
            $userLiked = $userLike->getUserLiked();
            $match = $this->matchByLike($userLiked, $entityManager);
            $heart = true;
        } else {
            $userLiker->removeUserLike($userLike);
            /** @var User */
            $userLiked = $userLike->getUserLiked();
            $this->deleteMatch($userLiked, $entityManager, $matchRepo);
            $entityManager->remove($userLike);
        }

        $entityManager->flush();

        return $this->json([
            'heart' => $heart,
            'match' => $match
        ]);
    }

    private function matchByLike(User $userLiked, EntityManagerInterface $entityManager): bool
    {
        /** @var User */
        $userLiker = $this->getUser();

        if ($userLiked->getOneUserLike($userLiker)) {
            $match = new MatchByLike();

            $match->setMatchedAt(new DateTimeImmutable());
            $match->setUserLiker($userLiker);
            $match->setUserLiked($userLiked);

            $entityManager->persist($match);
            $entityManager->flush();

            return true;
        }

        return false;
    }

    private function deleteMatch(
        User $userLiked,
        EntityManagerInterface $entityManager,
        MatchByLikeRepository $matchRepo
    ): void {
        /** @var User */
        $userLiker = $this->getUser();

        $match = $matchRepo->findOneBy([
            'userLiker' => $userLiker,
            'userLiked' => $userLiked
        ]);

        if ($match) {
            $entityManager->remove($match);
        }
    }

    public function matchCount(MatchByLikeRepository $matchRepo): Response
    {
        /** @var User */
        $user = $this->getUser();

        $match = $matchRepo->findByUser($user);

        $matchCount = count($match);

        return $this->render('includes/_matchCount.html.twig', [
            'matchCount' => $matchCount
        ]);
    }
}
