<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author_id = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'carts')]
    private Collection $article_id;

    public function __construct()
    {
        $this->article_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): ?User
    {
        return $this->author_id;
    }

    public function setAuthorId(User $author_id): static
    {
        $this->author_id = $author_id;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticleId(): Collection
    {
        return $this->article_id;
    }

    public function addArticleId(Article $articleId): static
    {
        if (!$this->article_id->contains($articleId)) {
            $this->article_id->add($articleId);
        }

        return $this;
    }

    public function removeArticleId(Article $articleId): static
    {
        $this->article_id->removeElement($articleId);

        return $this;
    }

    public function getTotalPrice(): float
    {
        $total = 0;

        foreach ($this->getArticleId() as $article) {
            $total += $article->getPrice();
        }

        return $total;
    }
}
