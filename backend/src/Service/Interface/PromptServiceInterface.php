<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Prompt;

interface PromptServiceInterface
{
    public function generatePrompt(): Prompt;
}