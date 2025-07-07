<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Model\Puzzle;

interface PuzzleServiceInterface
{
    public function generatePuzzle(): Puzzle;

    public function canRemoveCharsFromPuzzle(Puzzle $puzzle, FrequencyInterface $chars): bool;

    public function isValidDictionaryWord(string $word): bool;
}