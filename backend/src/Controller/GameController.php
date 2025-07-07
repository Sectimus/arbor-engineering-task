<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Exception\NotEnoughCharsException;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;
use Acme\CountUp\Service\Interface\WordServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameController extends AbstractController
{
    public function __construct(
        private PuzzleServiceInterface $puzzleService,
        private ChallengeServiceInterface $challengeService,
        private WordServiceInterface $wordService,
    ){}

    private function getCurrentChallenge(SessionInterface $session): Challenge
    {
        $challenge = $session->get('challenge');
        if(!$challenge instanceof Challenge){
            return $this->newChallenge($session);
        }

        return $challenge;
    }

    private function newChallenge(SessionInterface $session): Challenge{
        // create a brand new puzzle to use with this challenge. (Instead of using an existing one, which could be exist on the session)
        $puzzle = $this->puzzleService->generatePuzzle();

        //TODO remove me OVERRIDE
        $puzzle->setText("example");

        $challenge = $this->challengeService->createChallenge($puzzle);

        //place the challenge in the session so the user can return later
        $session->set('challenge', $challenge);

        return $challenge;
    }

    public function newChallengeAction(Request $request): JsonResponse
    {
        $challenge = $this->newChallenge($request->getSession());

        return $this->successResponse($challenge);
    }

    /**
     * Gets the current challenge, or returns a new one if this is a new session.
     */
    public function getChallengeAction(Request $request): JsonResponse
    {
        $challenge = $this->getCurrentChallenge($request->getSession());

        return $this->successResponse($challenge);
    }

    /**
     * Verifies a submission against a challenge
     */
    public function submitChallengeAction(Request $request): JsonResponse
    {
        //TODO validation maybe?
        $answer = $request->getPayload()->get('answer');
        if($answer === null || !is_string($answer)){
            throw new Exception("'answer' should be a valid string");
        }

        $challenge = $this->getCurrentChallenge($request->getSession());

        if(!$this->wordService->isValidDictionaryWord($answer)){
            return $this->errorReponse("The provided word is not a valid word in the english dictionary", $challenge);
        }

        $this->puzzleService->canRemoveCharsFromPuzzle($challenge->getPuzzle(), new CharFrequency($answer));

        try{
            $challenge = $this->challengeService->submitChallenge($challenge, $answer);
        } catch(NotEnoughCharsException $e){
            return $this->errorReponse("Not enough characters available to use that word", $challenge);
        }

        $request->getSession()->set('challenge', $challenge);

        // if(!$this->challengeService->isChallengeSolvable($challenge)){
        //     //challenge is complete, save this to the leaderboard
        // };

        return $this->successResponse($challenge);
    }

    /**
     * Completes the challenge for the current user
     */
    public function completeChallengeAction(Request $request): JsonResponse
    {
        $name = $request->getPayload()->get('name');
        if($name === null){
            throw new Exception("'name' should be provided but is missing");
        }
        if(!is_string($name)){
            throw new Exception("$name is not a string");
        }

        /** @var ?Challenge $challenge */
        $challenge = $request->getSession()->get('challenge');
        if(!($challenge instanceof Challenge)){
            //TODO: What do we do when they don't have a challenge?
            return $this->json(['error' => "You don't have an existing challenge, please return with a valid game session token."]);
        }

        $this->challengeService->completeChallenge($challenge, $name);

        // Find out what characters are left (so that we can search for anagrams)
        $freq = new CharFrequency($challenge->getPuzzle()->getText());
        $freq->subtractFrequency($challenge->getUsedChars());
        $solutions = $this->wordService->findAnagrams($freq->toString());

        return $this->json([
            'challenge' => $challenge->getPuzzle()->getText(),
            'used' => $challenge->getUsedChars()->getFrequencies(),
            'score' => $challenge->getScore(),
            'solutions' => $solutions,
        ]);
    }


    /**
     * TODO move these into a dedicated normalizer/serializer setup
     */
    private function successResponse(Challenge $challenge): JsonResponse{

        return $this->json([
            'challenge' => $challenge->getPuzzle()->getText(),
            'used' => $challenge->getUsedChars()->getFrequencies(),
            'score' => $challenge->getScore()
        ]);
    }
    private function errorReponse(string $message, ?Challenge $challenge = null): JsonResponse{
        if($challenge === null){
            return $this->json([
                'error' => $message
            ], 400);
        }
        return $this->json([
            'error' => $message,
            'challenge' => $challenge->getPuzzle()->getText(),
            'used' => $challenge->getUsedChars()->getFrequencies(),
            'score' => $challenge->getScore()
        ], 400);
    }
    // private function leaderboardResponse(): JsonResponse{

    //     return $this->json([
    //         'challenge' => $challenge->getPuzzle()->getText(),
    //         'used' => $challenge->getUsedChars()->getFrequencies(),
    //         'score' => $challenge->getScore()
    //     ]);
    // }
}