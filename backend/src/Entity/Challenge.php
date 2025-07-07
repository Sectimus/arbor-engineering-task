<?php
declare(strict_types=1);

//TODO move this to models
namespace Acme\CountUp\Entity;

final class Challenge
{
    private CharFrequency $usedChars;

    public function __construct(private Puzzle $puzzle)
    {
        //Start off a challenge with no used chars
        $this->usedChars = new CharFrequency("");
    }

    public function getPuzzle(): Puzzle
    {
        return $this->puzzle;
    }

    public function setPuzzle(Puzzle $puzzle): self
    {
        $this->puzzle = $puzzle;
        return $this;
    }

    public function addUsedChars(CharFrequency $chars): self
    {
        $this->usedChars->addFrequency($chars);
        return $this;
    }

    protected function setUsedChars(CharFrequency $usedChars): self
    {
        $this->usedChars = $usedChars;
        return $this;
    }

    public function getUsedChars(): CharFrequency
    {
        return $this->usedChars;
    }
}