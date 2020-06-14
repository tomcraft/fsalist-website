<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Watchlist::class, mappedBy="owner")
     */
    private $watchlists;

    /**
     * @ORM\OneToMany(targetEntity=MediaReview::class, mappedBy="author")
     */
    private $mediaReviews;

    /**
     * @ORM\OneToMany(targetEntity=MediaComment::class, mappedBy="author")
     */
    private $mediaComments;

    public function __construct()
    {
        $this->watchlists = new ArrayCollection();
        $this->mediaReviews = new ArrayCollection();
        $this->mediaComments = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return Watchlist
     */
    public function getMainWatchlist(): Watchlist
    {
        $mainWatchlist = $this->watchlists->first();
        if (!$mainWatchlist) {
            $mainWatchlist = new Watchlist();
            $this->addWatchlist($mainWatchlist);
        }
        return $mainWatchlist;
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

    /**
     * @return Collection|MediaReview[]
     */
    public function getMediaReviews(): Collection
    {
        return $this->mediaReviews;
    }

    public function addMediaReview(MediaReview $mediaReview): self
    {
        if (!$this->mediaReviews->contains($mediaReview)) {
            $this->mediaReviews[] = $mediaReview;
            $mediaReview->setAuthor($this);
        }

        return $this;
    }

    public function removeMediaReview(MediaReview $mediaReview): self
    {
        if ($this->mediaReviews->contains($mediaReview)) {
            $this->mediaReviews->removeElement($mediaReview);
            // set the owning side to null (unless already changed)
            if ($mediaReview->getAuthor() === $this) {
                $mediaReview->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MediaComment[]
     */
    public function getMediaComments(): Collection
    {
        return $this->mediaComments;
    }

    public function addMediaComment(MediaComment $mediaComment): self
    {
        if (!$this->mediaComments->contains($mediaComment)) {
            $this->mediaComments[] = $mediaComment;
            $mediaComment->setAuthor($this);
        }

        return $this;
    }

    public function removeMediaComment(MediaComment $mediaComment): self
    {
        if ($this->mediaComments->contains($mediaComment)) {
            $this->mediaComments->removeElement($mediaComment);
            // set the owning side to null (unless already changed)
            if ($mediaComment->getAuthor() === $this) {
                $mediaComment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
