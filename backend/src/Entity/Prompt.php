<?php
declare(strict_types=1);

namespace Acme\CountUp\Entity;

use Doctrine\ORM\Mapping as ORM;

//TODO prompts should implicitly be unique per character. Such that there doesn't exist both ABCDE and ABECD

#[ORM\Entity]
final class Prompt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private string $text;

    #[ORM\Column(length: 2)]
    private string $lang;

    //TODO leaderboard should also go here so that they are grouped per prompt

    public function getId(): ?int
    {
        return $this->id;
    }

    protected function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }
}