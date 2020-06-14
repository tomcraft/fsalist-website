<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WatchlistMediaRepository")
 */
class WatchlistMedia
{

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=Watchlist::class, inversedBy="medias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $watchlist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mediaType;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $mediaId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $seen = false;

    public function getWatchlist(): ?Watchlist
    {
        return $this->watchlist;
    }

    public function setWatchlist(?Watchlist $watchlist): self
    {
        $this->watchlist = $watchlist;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getMediaId(): ?int
    {
        return $this->mediaId;
    }

    public function setMediaId(int $mediaId): self
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): self
    {
        $this->seen = $seen;

        return $this;
    }
}
