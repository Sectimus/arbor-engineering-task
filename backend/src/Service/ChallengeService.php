<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\Prompt;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\PromptServiceInterface;
use Exception;

class ChallengeService implements ChallengeServiceInterface
{
    public function __construct(private PromptServiceInterface $promptService)
    {}

    public function createChallenge(Prompt $prompt): Challenge { 
        $challenge = new Challenge();
        $challenge->setPrompt($prompt);
        // $gameSession = bin2hex(random_bytes(16));
        // $challenge->setGameSession($gameSession);
        return $challenge;
    }

    public function submitChallenge(Challenge $challenge, string $answer): Challenge { 
        // Using modeset=1 to ensure we don't get an array for every ASCII character, but only the ones we hit.
        // $subFrequency = $this->checkIfWithinFrequency($challenge->getPrompt()->getText(), $answer);
        $charsAreWithinPrompt = $this->promptService->charsAreWithinPrompt($challenge->getPrompt(), 'feel');


        return new Challenge();
    }
}