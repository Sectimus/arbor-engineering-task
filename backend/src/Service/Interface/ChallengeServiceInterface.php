<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Model\Challenge;
use Acme\CountUp\Model\Puzzle;

interface ChallengeServiceInterface
{
    /**
     * Create a new challenge from a puzzle
     */
    public function createChallenge(Puzzle $puzzle): Challenge;

    /**
     * Submit a single answer to a challenge.
     */
    public function submitChallenge(Challenge $challenge, string $answer): Challenge;

    /**
     * Mark the challenge as complete, referenced by the `$name`
     */
    public function completeChallenge(Challenge $challenge, string $name): void;

    /**
     * Returns a few possible answers for the given challenge, if there are any.
     * @return array<string>
     */
    public function getSolutions(Challenge $challenge): array;
}