<?php

namespace App\Entity;

use App\Repository\RomeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RomeRepository::class)
 */
class Rome
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=RegisteredUser::class, mappedBy="rome")
     */
    private Collection $registeredUsers;

    public function __construct()
    {
        $this->registeredUsers = new ArrayCollection();
    }

    public function __serialize(): array
    {
        return [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|RegisteredUser[]
     */
    public function getRegisteredUsers(): Collection
    {
        return $this->registeredUsers;
    }

    public function addRegisteredUser(RegisteredUser $registeredUser): self
    {
        if (!$this->registeredUsers->contains($registeredUser)) {
            $this->registeredUsers[] = $registeredUser;
            $registeredUser->setRome($this);
        }

        return $this;
    }

    public function removeRegisteredUser(RegisteredUser $registeredUser): self
    {
        if ($this->registeredUsers->removeElement($registeredUser)) {
            // set the owning side to null (unless already changed)
            if ($registeredUser->getRome() === $this) {
                $registeredUser->setRome(null);
            }
        }

        return $this;
    }
}
