<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * [Description Author].
 */
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    /**
     * [Description for $id].
     *
     * @var int|null|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * [Description for $firstname].
     *
     * @var string|null|null
     */
    #[ORM\Column(length: 25)]
    private ?string $firstname = null;

    /**
     * [Description for $lastname].
     *
     * @var string|null|null
     */
    #[ORM\Column(length: 25)]
    private ?string $lastname = null;

    /**
     * [Description for $email].
     *
     * @var string|null|null
     */
    #[ORM\Column(length: 100)]
    private ?string $email = null;

    /** @var Collection<int, Article> */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class, orphanRemoval: true)]
    private Collection $articles;

    /**
     * [Description for __construct].
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * [Description for getId].
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * [Description for getFirstname].
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * [Description for setFirstname].
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * [Description for getLastname].
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * [Description for setLastname].
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * [Description for getEmail].
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * [Description for setEmail].
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * [Description for addArticle].
     */
    public function addArticle(Article $article): self
    {
        if (false === $this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setAuthor($this);
        }

        return $this;
    }

    /**
     * [Description for removeArticle].
     */
    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }
}
