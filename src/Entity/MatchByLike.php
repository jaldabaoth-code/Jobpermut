<?php

namespace App\Entity;

use App\Repository\MatchByLikeRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchByLikeRepository::class)
 */
class MatchByLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $userLiker;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $userLiked;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $matchedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLiker(): ?User
    {
        return $this->userLiker;
    }

    public function setUserLiker(?User $userLiker): self
    {
        if ($userLiker !== null) {
            $this->userLiker = $userLiker;
        }

        return $this;
    }

    public function getUserLiked(): ?User
    {
        return $this->userLiked;
    }

    public function setUserLiked(?User $userLiked): self
    {
        if ($userLiked !== null) {
            $this->userLiked = $userLiked;
        }

        return $this;
    }

    public function getMatchedAt(): ?\DateTimeInterface
    {
        return $this->matchedAt;
    }

    public function setMatchedAt(\DateTimeInterface $matchedAt): self
    {
        $this->matchedAt = $matchedAt;

        return $this;
    }
}
