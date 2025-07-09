<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Model\Puzzle;
use Acme\CountUp\Model\Interface\FrequencyInterface;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;

class PuzzleService implements PuzzleServiceInterface{
    // It would be a nice to have if this was an env var somewhere.
    private const PUZZLE_LENGTH = 20;

    /**
     * @inheritDoc
     */
    public function areCharactersInPuzzle(Puzzle $puzzle, FrequencyInterface $freq): bool {
        $puzzleFreq = new CharFrequency($puzzle->getText());

        return $puzzleFreq->containsFrequency($freq);
    }

    /**
     * @inheritDoc
     */
    public function generatePuzzle(string $seed = ''): Puzzle { 
        $requiredPaddingLength = self::PUZZLE_LENGTH - strlen($seed);
        $randomPadding = $this->generateRandomPadding($requiredPaddingLength);
        $randomString = str_shuffle(strtolower($randomPadding . $seed));
        
        $puzzle = new Puzzle()->setText($randomString);

        return $puzzle;
    }

    /**
     * Generates a random string of a given legnth to use in a puzzle
     */
    private function generateRandomPadding(int $length): string {
        //TODO duplicate alphabet?
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @inheritDoc
     */
    public function canRemoveCharsFromPuzzle(Puzzle $puzzle, FrequencyInterface $charFrequency): bool{
        $puzzleFreq = new CharFrequency($puzzle->getText());
        $puzzleFreq->subtractFrequency($charFrequency);

        // Check, if any character is less than 0 on frequency (if it is, then it's been used more than is allowed)
        $tooManyCharacters = array_any($puzzleFreq->getFrequencies(), fn($freq) => $freq < 0);
        
        return !$tooManyCharacters;
    }
}