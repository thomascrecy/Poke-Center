<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'stock')]
    private Collection $article_id;

    #[ORM\Column]
    private ?int $amount = null;

    public function __construct()
    {
        $this->article_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $articleId->setStock($this);
        }

        return $this;
    }

    public function removeArticleId(Article $articleId): static
    {
        if ($this->article_id->removeElement($articleId)) {
            // set the owning side to null (unless already changed)
            if ($articleId->getStock() === $this) {
                $articleId->setStock(null);
            }
        }

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
