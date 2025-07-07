<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\CharFrequency;
use Acme\CountUp\Entity\Puzzle;
use Acme\CountUp\Repository\WordRepository;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;

class PuzzleService implements PuzzleServiceInterface{
    private const PUZZLE_LENGTH = 20;
    public function __construct(
        private WordRepository $wordRepository,
    )
    {}

    public function isValidDictionaryWord(string $word): bool { 
        $word = $this->wordRepository->findOneBy(['term' => $word]);

        return $word !== null;
    }

    public function generatePuzzle(): Puzzle { 
        $seed = $this->wordRepository->getRandomWord();

        $requiredPaddingLength = self::PUZZLE_LENGTH - strlen($seed->getTerm());
        $randomPadding = $this->generateRandomPadding($requiredPaddingLength);
        $randomString = str_shuffle(strtolower($randomPadding . $seed->getTerm()));
        
        $puzzle = new Puzzle()->setLang('en')->setText($randomString);

        return $puzzle;
    }

    private function generateRandomPadding(int $length): string {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function canRemoveCharsFromPuzzle(Puzzle $puzzle, CharFrequency $charFrequency): bool{
        $puzzleFreq = new CharFrequency($puzzle->getText());
        $puzzleFreq->subtractFrequency($charFrequency);

        // Check, if any character is less than 0 on frequency (if it is, then it's been used more than is allowed)
        $tooManyCharacters = array_any($puzzleFreq->getFrequencies(), fn($hz) => $hz < 0);
        
        return !$tooManyCharacters;
    }
}