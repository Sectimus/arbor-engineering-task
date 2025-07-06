<?php
declare(strict_types=1);

//TODO move this to models
namespace Acme\CountUp\Entity;

final class Challenge
{
    private ?int $id = null;

    private Prompt $prompt;

    private string $usedChars = '';

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