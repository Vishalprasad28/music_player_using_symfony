<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Posts $postId = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $likedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): ?Posts
    {
        return $this->postId;
    }

    public function setPostId(?Posts $postId): self
    {
        $this->postId = $postId;

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
