<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Acme\CountUp\Entity\Challenge;
use Acme\CountUp\Entity\CharFrequency;
use Acme\CountUp\Entity\Puzzle;
use Acme\CountUp\Exception\ChallengeException;
use Acme\CountUp\Exception\InvalidDictionaryWordException;
use Acme\CountUp\Exception\NotEnoughCharsException;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;
use Acme\CountUp\Service\WordService;
use Exception;
use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    public function __construct(
        private PuzzleServiceInterface $puzzleService,
        private ChallengeServiceInterface $challengeService,
        private WordService $wordService,
    ){}

    public function newChallenge(Request $request): JsonResponse
    {
        // create a brand new puzzle to use with this challenge. (Instead of using an existing one, which could be linked with an existing leaderboard)
        $puzzle = $this->puzzleService->generatePuzzle();

        //TODO remove me OVERRIDE
        $puzzle->setText("example");

        $challenge = $this->challengeService->createChallenge($puzzle);

        //place the challenge in the session so the user can return later
        $request->getSession()->set('challenge', $challenge);

        // Serialize and return as json.
        return $this->successResponse($challenge);
    }

    /**
     * Gets the current challenge, or returns a new one if this is a new session.
     */
    public function getChallenge(Request $request): JsonResponse
    {
        $challenge = $request->getSession()->get('challenge');
        if(!($challenge instanceof Challenge)){
            return $this->newChallenge($request);
        }

        return $this->successResponse($challenge);
    }

    /**
     * Verifies a submission against a challenge
     */
    public function submitChallenge(Request $request): JsonResponse
    {
        $answer = $request->getPayload()->get('answer');
        if($answer === null){
            throw new Exception("'answer' should be provided but is missing");
        }
        if(!is_string($answer)){
            //TODO: handle numerical answers or answers with punctuation (any non (A-Z)).
            throw new Exception("$answer is not a string");
        }

        $challenge = $request->getSession()->get('challenge');
        if(!($challenge instanceof Challenge)){
            //TODO: What do we do when they don't have a challenge?
            return $this->json(['error' => "You don't have an existing challenge, please return with a valid game session token."]);
        }

        try{
            $challenge = $this->challengeService->submitChallenge($challenge, $answer);
        } catch(InvalidDictionaryWordException $e){
            return $this->errorReponse($challenge, "The provided word is not a valid word in the english dictionary");
        } catch(NotEnoughCharsException $e){
            return $this->errorReponse($challenge, "Not enough characters available to use that word");
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
    public function completeChallenge(Request $request): JsonResponse
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

        //final words you could have chosen.
        $solutions = $this->challengeService->getPossibleSolutions($challenge);

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
    private function errorReponse(Challenge $challenge, string $message): JsonResponse{
        return $this->json([
            'error' => $message,
            'challenge' => $challenge->getPuzzle()->getText(),
            'used' => $challenge->getUsedChars()->getFrequencies(),
            'score' => $challenge->getScore()
        ]);
    }
    // private function leaderboardResponse(): JsonResponse{

    //     return $this->json([
    //         'challenge' => $challenge->getPuzzle()->getText(),
    //         'used' => $challenge->getUsedChars()->getFrequencies(),
    //         'score' => $challenge->getScore()
    //     ]);
    // }
}