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

    public function charsAreWithinPuzzle(Puzzle $puzzle, string $chars): bool{
        $puzzleFreq = new CharFrequency($puzzle->getText());
        $charFreq = new CharFrequency($chars);
        $freqDelta = $puzzleFreq->subtractFrequency($charFreq);

        // Check, if any character is less than 0 on frequency (if it is, then it's been used more than is allowed)
        $test = array_any($freqDelta, fn($hz) => $hz < 0);




        // foreach ($charsInAnswer as $char => $frequencey) {

        //     if($frequencey > 0 && isset($totalChars[$char]) && $totalChars[$char] > 0){
        //         $frequenceyDelta = $totalChars[$char] - $frequencey;

        //         if($frequenceyDelta < 0){
        //             // Not enough instances of the correct character are available.
        //             return false;
        //         } else{
        //             //There are enough instance of this char, move on to checking the next char.
        //             continue;
        //         } 
        //     } else{
        //         return false;
        //     }
        // }

        return true;
    }
}