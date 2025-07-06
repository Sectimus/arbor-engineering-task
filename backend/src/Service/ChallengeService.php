<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Challenge;
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
        $challenge = new Challenge();
        $challenge->setPuzzle($puzzle);
        return $challenge;
    }

    public function submitChallenge(Challenge $challenge, string $answer): Challenge { 
        // Using modeset=1 to ensure we don't get an array for every ASCII character, but only the ones we hit.
        $charsAreWithinPuzzle = $this->puzzleService->charsAreWithinPuzzle($challenge->getPuzzle(), $answer);

        if(!$charsAreWithinPuzzle){
            // The provided answer characters do not appear enough times in the challenge
            throw new NotEnoughCharsException();
        }

        //ensure it is a valid word we were provided.
        $isValidWord = $this->puzzleService->isValidDictionaryWord($answer);

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