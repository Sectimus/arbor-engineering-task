<?php
declare(strict_types=1);

namespace Acme\CountUp\Entity;

// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity]
final class Challenge
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    private ?int $id = null;

    // #[ORM\ManyToOne(targetEntity: Prompt::class)]
    // #[ORM\JoinColumn(nullable: false)]
    private Prompt $prompt;

    private string $usedChars;

    public function getId(): ?int
    {
        return $this->id;
    }

    protected function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPrompt(): Prompt
    {
        return $this->prompt;
    }

    public function setPrompt(Prompt $prompt): self
    {
        $this->prompt = $prompt;
        return $this;
    }

    public function addUsedChars(string $chars): self
    {
        $this->usedChars .= $chars;
        
        return $this;
    }

    public function getUsedChars(): string
    {
        return $this->usedChars;
    }
}