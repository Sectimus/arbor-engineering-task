<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\Puzzle;
use Acme\CountUp\Entity\Word;

interface ChallengeServiceInterface
{
    public function createChallenge(Puzzle $puzzle): Challenge;

    public function submitChallenge(Challenge $challenge, string $answer): Challenge;

    public function completeChallenge(Challenge $challenge, string $name): void;

    /**
     * @return array<string>
     */
    public function getPossibleSolutions(Challenge $challenge): array;
}