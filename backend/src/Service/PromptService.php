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
}