<?php

namespace App\Entity;

use App\Repository\MediaCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaCommentRepository::class)
 */
class MediaComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mediaComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $mediaId;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=MediaComment::class, inversedBy="mediaComments")
     */
    private $parentComment;

    /**
     * @ORM\OneToMany(targetEntity=MediaComment::class, mappedBy="parentComment")
     */
    private $mediaComments;

    /**
     * @ORM\Column(type="integer")
     */
    private $thumbsUp;

    /**
     * @ORM\Column(type="integer")
     */
    private $thumbsDown;

    public function __construct()
    {
        $this->mediaComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMediaId(): ?int
    {
        return $this->mediaId;
    }

    public function setMediaId(int $mediaId): self
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getParentComment(): ?self
    {
        return $this->parentComment;
    }

    public function setParentComment(?self $parentComment): self
    {
        $this->parentComment = $parentComment;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMediaComments(): Collection
    {
        return $this->mediaComments;
    }

    public function addMediaComment(self $mediaComment): self
    {
        if (!$this->mediaComments->contains($mediaComment)) {
            $this->mediaComments[] = $mediaComment;
            $mediaComment->setParentComment($this);
        }

        return $this;
    }

    public function removeMediaComment(self $mediaComment): self
    {
        if ($this->mediaComments->contains($mediaComment)) {
            $this->mediaComments->removeElement($mediaComment);
            // set the owning side to null (unless already changed)
            if ($mediaComment->getParentComment() === $this) {
                $mediaComment->setParentComment(null);
            }
        }

        return $this;
    }

    public function getThumbsUp(): ?int
    {
        return $this->thumbsUp;
    }

    public function setThumbsUp(int $thumbsUp): self
    {
        $this->thumbsUp = $thumbsUp;

        return $this;
    }

    public function getThumbsDown(): ?int
    {
        return $this->thumbsDown;
    }

    public function setThumbsDown(int $thumbsDown): self
    {
        $this->thumbsDown = $thumbsDown;

        return $this;
    }
}
