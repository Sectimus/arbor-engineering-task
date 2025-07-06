<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\PromptServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    public function __construct(
        private PromptServiceInterface $promptService,
        private ChallengeServiceInterface $challengeService,
    ){}

    public function newChallenge(Request $request): JsonResponse
    {
        // create a brand new prompt to use with this challenge. (Instead of using an existing one, which could be linked with an existing leaderboard)
        $prompt = $this->promptService->generatePrompt();

        $challenge = $this->challengeService->createChallenge($prompt);

        //place the challenge in the session so the user can return
        $request->getSession()->set('challenge', $challenge);

        // Serialize and return as json.
        return $this->json(['challenge' => $prompt->getText()]);
    }

    // public function replaceChallenge(Request $request): JsonResponse{
    //     //find an existing challenge (that someone has completed at least once) in the database
    // }

    /**
     * Gets the current challenge, or returns a new one if this is a new session.
     */
    public function getChallenge(Request $request): JsonResponse
    {
        $challenge = $request->getSession()->get('challenge');
        if(!($challenge instanceof Challenge)){
            return $this->newChallenge($request);
        }

        return $this->json(['challenge' => $challenge->getPrompt()->getText()]);
    }
}