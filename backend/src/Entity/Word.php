<?php
declare(strict_types=1);

namespace Acme\CountUp\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Word
{
    #[ORM\Id]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 45)]
    private string $term;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTerm(): string
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $term;
        return $this;
    }
}