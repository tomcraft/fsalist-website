<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WatchlistRepository")
 */
class Watchlist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="watchlists")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $shareId;

    /**
     * @ORM\OneToMany(targetEntity=WatchlistMedia::class, mappedBy="watchlist", orphanRemoval=true)
     */
    private $medias;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getShareId(): ?string
    {
        return $this->shareId;
    }

    public function setShareId(string $shareId): self
    {
        $this->shareId = $shareId;

        return $this;
    }

    /**
     * @return Collection|WatchlistMedia[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(WatchlistMedia $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setWatchlist($this);
        }

        return $this;
    }

    public function removeMedia(WatchlistMedia $media): self
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
            // set the owning side to null (unless already changed)
            if ($media->getWatchlist() === $this) {
                $media->setWatchlist(null);
            }
        }

        return $this;
    }
}
