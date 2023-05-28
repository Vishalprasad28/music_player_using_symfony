<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $songName = null;

    #[ORM\Column(length: 255)]
    private ?string $singerName = null;

    #[ORM\Column(length: 255)]
    private ?string $genere = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'song', targetEntity: LikesCount::class, orphanRemoval: true)]
    private Collection $likesCounts;

    public function __construct()
    {
        $this->likesCounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSongName(): ?string
    {
        return $this->songName;
    }

    public function setSongName(string $songName): self
    {
        $this->songName = $songName;

        return $this;
    }

    public function getSingerName(): ?string
    {
        return $this->singerName;
    }

    public function setSingerName(string $singerName): self
    {
        $this->singerName = $singerName;

        return $this;
    }

    public function getGenere(): ?string
    {
        return $this->genere;
    }

    public function setGenere(string $genere): self
    {
        $this->genere = $genere;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
            $likesCount->setSong($this);
        }

        return $this;
    }

    public function removeLikesCount(LikesCount $likesCount): self
    {
        if ($this->likesCounts->removeElement($likesCount)) {
            // set the owning side to null (unless already changed)
            if ($likesCount->getSong() === $this) {
                $likesCount->setSong(null);
            }
        }

        return $this;
    }

}
