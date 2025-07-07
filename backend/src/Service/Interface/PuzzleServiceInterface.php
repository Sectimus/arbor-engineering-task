<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Entity\CharFrequency;
use Acme\CountUp\Entity\Puzzle;

interface PuzzleServiceInterface
{
    public function generatePuzzle(): Puzzle;

    public function canRemoveCharsFromPuzzle(Puzzle $puzzle, CharFrequency $chars): bool;

    public function isValidDictionaryWord(string $word): bool;
}