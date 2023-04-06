<?php

namespace App\Entity;

use App\Repository\LikesCountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesCountRepository::class)]
class LikesCount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likesCounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Posts $song = null;

    #[ORM\ManyToOne(inversedBy: 'likesCounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $likedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSong(): ?Posts
    {
        return $this->song;
    }

    public function setSong(?Posts $song): self
    {
        $this->song = $song;

        return $this;
    }

    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    public function setLikedBy(?User $likedBy): self
    {
        $this->likedBy = $likedBy;

        return $this;
    }
}
