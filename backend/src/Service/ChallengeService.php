<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\CharFrequency;
use Acme\CountUp\Entity\Puzzle;
use Acme\CountUp\Exception\InvalidDictionaryWordException;
use Acme\CountUp\Exception\NotEnoughCharsException;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;
use Exception;

class ChallengeService implements ChallengeServiceInterface
{
    public function __construct(private PuzzleServiceInterface $puzzleService)
    {}

    public function createChallenge(Puzzle $puzzle): Challenge { 
        $challenge = new Challenge($puzzle);
        return $challenge;
    }

    public function submitChallenge(Challenge $challenge, string $answer): Challenge { 
        $freq = new CharFrequency($answer);
        // Ensure that we are using a sum of all previous answers in our tally.
        $freq->addFrequency($challenge->getUsedChars());

        $puzzle = $this->puzzleService->canRemoveCharsFromPuzzle($challenge->getPuzzle(), $freq);
        if($puzzle === false){
            // Could not remove the chars from the puzzle as it would use a character more than allowed.
            throw new NotEnoughCharsException();
        }

        // Ensure it is a valid word we were provided.
        $isValidWord = $this->puzzleService->isValidDictionaryWord($answer);
        if(!$isValidWord){
            // The provided answer is not a real dictionary word.
            throw new InvalidDictionaryWordException();
        }

        // We consider the challenge answered correctly at this point. (there could still be more answers remaining)
        $challenge->addUsedChars(new CharFrequency($answer));

        return $challenge;
    }

    public function getSolutions(Challenge $challenge): array{
        return [];
    }
    
}