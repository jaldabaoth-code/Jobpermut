<?php

namespace App\Entity;

use App\Repository\UserLikeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=UserLikeRepository::class)
 */
class UserLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $userLiker;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likedBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $userLiked;

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
}
