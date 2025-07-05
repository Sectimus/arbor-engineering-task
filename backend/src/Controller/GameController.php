<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Acme\CountUp\Service\PromptServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    public function __construct(
        private PromptServiceInterface $promptService,
    ){}

    public function getChallenge(Request $request): JsonResponse
    {
        $prompt = $this->promptService->generatePrompt();
        // Serialize and return as json.
        return $this->json(['uuid' => 'super_secret_key', 'challenge' => $prompt->getText()]);
    }
}