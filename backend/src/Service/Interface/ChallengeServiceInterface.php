<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\Puzzle;

interface ChallengeServiceInterface
{
    public function createChallenge(Puzzle $puzzle): Challenge;

    public function submitChallenge(Challenge $challenge, string $answer): Challenge;

    /**
     * @return array<string>
     */
    public function getSolutions(Challenge $challenge): array;
}