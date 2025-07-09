<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Model\Interface\FrequencyInterface;
use Acme\CountUp\Model\Puzzle;

interface PuzzleServiceInterface
{
    /**
     * Generates a random puzzle with an optional seed string
     */
    public function generatePuzzle(String $seed = ''): Puzzle;

    /**
     * Returns true if all `$chars` could be removed from the puzzle without issue.
     */
    public function canRemoveCharsFromPuzzle(Puzzle $puzzle, FrequencyInterface $chars): bool;

    /**
     * Returns true if all `$chars` exist at least once in the puzzle.
     */
    public function areCharactersInPuzzle(Puzzle $puzzle, FrequencyInterface $chars): bool;
}