<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\CharFrequency;
use Acme\CountUp\Entity\Prompt;
use Acme\CountUp\Repository\WordRepository;
use Acme\CountUp\Service\Interface\PromptServiceInterface;

class PromptService implements PromptServiceInterface{
    private const PROMPT_LENGTH = 20;
    public function __construct(
        private WordRepository $wordRepository,
    )
    {}

    public function isValidDictionaryWord(string $word): bool { 
        $word = $this->wordRepository->findOneBy(['term' => $word]);

        return $word !== null;
    }

    public function generatePrompt(): Prompt { 
        $seed = $this->wordRepository->getRandomWord();

        $requiredPaddingLength = self::PROMPT_LENGTH - strlen($seed->getTerm());
        $randomPadding = $this->generateRandomPadding($requiredPaddingLength);
        $randomString = str_shuffle(strtolower($randomPadding . $seed->getTerm()));
        
        $prompt = new Prompt()->setLang('en')->setText($randomString);

        return $prompt;
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

    public function charsAreWithinPrompt(Prompt $prompt, string $chars): bool{
        $promptFreq = new CharFrequency($prompt->getText());
        $charFreq = new CharFrequency($chars);
        $freqDelta = $promptFreq->subtractFrequency($charFreq);

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