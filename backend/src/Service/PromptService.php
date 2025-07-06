<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Prompt;
use Acme\CountUp\Repository\WordRepository;
use Acme\CountUp\Service\Interface\PromptServiceInterface;

class PromptService implements PromptServiceInterface{
    private const PROMPT_LENGTH = 20;
    public function __construct(
        private WordRepository $wordRepository,
    )
    {}

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
        $totalChars = $this->getCharFrequencyArray($prompt->getText());
        $charsInAnswer = $this->getCharFrequencyArray($chars);


        foreach ($charsInAnswer as $char => $frequencey) {

            if($frequencey > 0 && isset($totalChars[$char]) && $totalChars[$char] > 0){
                $frequenceyDelta = $totalChars[$char] - $frequencey;

                if($frequenceyDelta < 0){
                    // Not enough instances of the correct character are available.
                    return false;
                } else{
                    //There are enough instance of this char, move on to checking the next char.
                    continue;
                } 
            } else{
                return false;
            }
        }

        return true;
    }

    /**
     * Converts a string into a 1d array representing how many occurances each character has appeared. Keyed by the ASCII value.
     * @return array<char, int>
     */
    private function getCharFrequencyArray(string $input): array{
        $charFrequencyArray = [];
        foreach (count_chars($input, 1) as $i => $val) {
            $char = chr($i);
            $charFrequencyArray[$char] = $val;
        }
        return $charFrequencyArray;
    }

    /**
     * Subtracts one frequency array with another, returning only the values that were not matched.
     * @param array<char, int> $a The original ocurrance array 
     * @param array<char, int> $b The occurrance array to subtract from the original
     * @return array<char, int> Any left over characters after the subtraction
     */
    private function subtractFrequency(array $a, array $b): array{
        return [];
    }
}