<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Prompt;

class PromptService implements PromptServiceInterface{
    public function generatePrompt(): Prompt { 
        $prompt = new Prompt()->setLang('en')->setText('dfgscrambledasd');

        return $prompt;
    }
}