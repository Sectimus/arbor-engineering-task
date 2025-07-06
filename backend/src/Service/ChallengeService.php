<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\Prompt;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;

class ChallengeService implements ChallengeServiceInterface
{
    public function __construct()
    {}

    public function createChallenge(Prompt $prompt): Challenge { 
        $challenge = new Challenge();
        $challenge->setPrompt($prompt);
        // $gameSession = bin2hex(random_bytes(16));
        // $challenge->setGameSession($gameSession);
        return $challenge;
    }
    
}