<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\Prompt;
use Acme\CountUp\Exception\InvalidDictionaryWordException;
use Acme\CountUp\Exception\NotEnoughCharsException;
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
        return $challenge;
    }

    public function submitChallenge(Challenge $challenge, string $answer): Challenge { 
        // Using modeset=1 to ensure we don't get an array for every ASCII character, but only the ones we hit.
        $charsAreWithinPrompt = $this->promptService->charsAreWithinPrompt($challenge->getPrompt(), $answer);

        if(!$charsAreWithinPrompt){
            // The provided answer characters do not appear enough times in the challenge
            throw new NotEnoughCharsException();
        }

        //ensure it is a valid word we were provided.
        $isValidWord = $this->promptService->isValidDictionaryWord($answer);

        if(!$isValidWord){
            // The provided answer is not a real dictionary word.
            throw new InvalidDictionaryWordException();
        }

        return $challenge;
    }

    public function getSolutions(Challenge $challenge): array{
        return [];
    }
    
}