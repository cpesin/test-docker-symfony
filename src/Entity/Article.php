<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * [Description Article].
 */
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    /**
     * [Description for $id].
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * [Description for $title].
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * [Description for $text].
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    /**
     * [Description for $state].
     */
    #[ORM\Column]
    private ?bool $state = null;

    /**
     * [Description for $created].
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    /**
     * [Description for $updated].
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated = null;

    /**
     * [Description for $author].
     */
    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    /**
     * [Description for getId].
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * [Description for getTitle].
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * [Description for setTitle].
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * [Description for getText].
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * [Description for setText].
     */
    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * [Description for isState].
     */
    public function isState(): ?bool
    {
        return $this->state;
    }

    /**
     * [Description for setState].
     */
    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * [Description for getCreated].
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * [Description for setCreated].
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * [Description for getUpdated].
     */
    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * [Description for setUpdated].
     */
    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * [Description for getAuthor].
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * [Description for setAuthor].
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
