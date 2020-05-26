<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Watchlist", mappedBy="owner", orphanRemoval=true)
     */
    private $watchlists;

    public function __construct()
    {
        $this->watchlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    /**
     * @return Collection|Watchlist[]
     */
    public function getWatchlists(): Collection
    {
        return $this->watchlists;
    }

    public function addWatchlist(Watchlist $watchlist): self
    {
        if (!$this->watchlists->contains($watchlist)) {
            $this->watchlists[] = $watchlist;
            $watchlist->setOwner($this);
        }

        return $this;
    }

    public function removeWatchlist(Watchlist $watchlist): self
    {
        if ($this->watchlists->contains($watchlist)) {
            $this->watchlists->removeElement($watchlist);
            // set the owning side to null (unless already changed)
            if ($watchlist->getOwner() === $this) {
                $watchlist->setOwner(null);
            }
        }

        return $this;
    }
}
