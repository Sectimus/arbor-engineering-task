<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Entity\Prompt;

interface PromptServiceInterface
{
    public function generatePrompt(): Prompt;

    public function charsAreWithinPrompt(Prompt $prompt, string $chars): bool;
}