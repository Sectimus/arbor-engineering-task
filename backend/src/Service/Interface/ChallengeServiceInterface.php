<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\Prompt;

interface ChallengeServiceInterface
{
    public function createChallenge(Prompt $prompt): Challenge;

    public function submitChallenge(Challenge $prompt, string $answer): Challenge;
}