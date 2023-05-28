<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userName = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $interests = null;

    #[ORM\Column(length: 300)]
    private ?string $profilePic = 'profilePic/default.png';

    #[ORM\OneToMany(mappedBy: 'likedBy', targetEntity: LikesCount::class, orphanRemoval: true)]
    private Collection $likesCounts;

    public function __construct()
    {
        $this->likesCounts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->interests;
    }

    public function setInterests(string $interets): self
    {
        $this->interests = $interets;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profilePic;
    }

    public function setProfilePic(string $profilePic): self
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    /**
     * @return Collection<int, LikesCount>
     */
    public function getLikesCounts(): Collection
    {
        return $this->likesCounts;
    }

    public function addLikesCount(LikesCount $likesCount): self
    {
        if (!$this->likesCounts->contains($likesCount)) {
            $this->likesCounts->add($likesCount);
            $likesCount->setLikedBy($this);
        }

        return $this;
    }

    public function removeLikesCount(LikesCount $likesCount): self
    {
        if ($this->likesCounts->removeElement($likesCount)) {
            // set the owning side to null (unless already changed)
            if ($likesCount->getLikedBy() === $this) {
                $likesCount->setLikedBy(null);
            }
        }

        return $this;
    }

}
