<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Acme\CountUp\Model\Challenge;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;
use Acme\CountUp\Service\Interface\WordServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Regex;

class GameController extends AbstractController
{
    public function __construct(
        private PuzzleServiceInterface $puzzleService,
        private ChallengeServiceInterface $challengeService,
        private WordServiceInterface $wordService,
    ){}

    /**
     * Returns the current challenge for the user session, or creates one if it does not exist.
     */
    private function getCurrentChallenge(SessionInterface $session): Challenge
    {
        $challenge = $session->get('challenge');
        if(!$challenge instanceof Challenge){
            return $this->newChallenge($session);
        }

        return $challenge;
    }

    /**
     * Creates a brand new challenge and sets it on the user session.
     */
    private function newChallenge(SessionInterface $session): Challenge{
        $seedString = $this->wordService->getRandomWord();
        $puzzle = $this->puzzleService->generatePuzzle($seedString);

        //OVERRIDE THE PROMPT DURING DEV
        // $puzzle->setText("dgeftoikbvxua");

        $challenge = $this->challengeService->createChallenge($puzzle);

        //place the challenge in the session so the user can return later
        $session->set('challenge', $challenge);

        return $challenge;
    }

    /**
     * Creates a new challenge and returns a response
     */
    public function newChallengeAction(Request $request): JsonResponse
    {
        $challenge = $this->newChallenge($request->getSession());

        return $this->successResponse($challenge);
    }

    /**
     * Gets the current challenge, or returns a new one if this is a new session and returns a response.
     */
    public function getChallengeAction(Request $request): JsonResponse
    {
        $challenge = $this->getCurrentChallenge($request->getSession());

        return $this->successResponse($challenge);
    }

    /**
     * Verifies a submission against the current challenge.
     */
    public function submitChallengeAction(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $answer = $request->getPayload()->get('answer');

        $notBlankConstraint = new NotBlank([
            'message' => 'Answer must not be blank'
        ]);
        $alphaConstraint = new Regex([
            'pattern' => '/^[a-z]+$/i',
            'message' => 'Answer must contain only letters, A-Z',
        ]);
        $errors = $validator->validate(
            $answer,
            [$notBlankConstraint, $alphaConstraint]
        );
        /** @var string $answer */
        

        if ($errors->count()) {
            // It would be better to return and print all errors at once, but outside of scope for now.
            return $this->errorReponse((string) $errors[0]->getMessage());
        }

        // If this is the first time that the user has appeared, they probably will get a new challenge here, but oh well! Guess again!
        $challenge = $this->getCurrentChallenge($request->getSession());

        if(!$this->wordService->isValidDictionaryWord($answer)){
            return $this->errorReponse("The provided word is not a valid word in the english dictionary", $challenge);
        }

        $answerCharFreq = new CharFrequency($answer);

        if(!$this->puzzleService->areCharactersInPuzzle($challenge->getPuzzle(), $answerCharFreq)){
            return $this->errorReponse("Please ensure all your characters exist in the puzzle", $challenge);
        };

        $answerCharFreq->addFrequency($challenge->getUsedChars());
        if (!$this->puzzleService->canRemoveCharsFromPuzzle($challenge->getPuzzle(), $answerCharFreq)){
            return $this->errorReponse("Not enough characters available to use that word", $challenge);
        }

        $challenge = $this->challengeService->submitChallenge($challenge, $answer);

        $request->getSession()->set('challenge', $challenge);

        return $this->successResponse($challenge);
    }

    /**
     * Completes the challenge for the current user
     */
    public function completeChallengeAction(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $name = $request->getPayload()->get('name');

        $notBlankConstraint = new NotBlank([
            'message' => 'Name must not be blank'
        ]);
        $alphaConstraint = new Regex([
            'pattern' => '/^[a-z]+$/i',
            'message' => 'Name must contain only letters, A-Z',
        ]);
        $errors = $validator->validate(
            $name,
            [$notBlankConstraint, $alphaConstraint]
        );
        if ($errors->count()) {
            // It would be better to return and print all errors at once, but outside of scope for now.
            return $this->errorReponse((string) $errors[0]->getMessage());
        }

        // Ensure the name is lowercase from here on out.
        $name = strtolower((string) $name);

        /** @var ?Challenge $challenge */
        $challenge = $request->getSession()->get('challenge');
        if(!($challenge instanceof Challenge)){
            return $this->errorReponse("You don't have an existing challenge, please return with a valid game session. Visit /api/challenge");
        }

        $this->challengeService->completeChallenge($challenge, $name);

        // Find out what characters are left (so that we can search for possible solutions)
        $freq = new CharFrequency($challenge->getPuzzle()->getText());
        $freq->subtractFrequency($challenge->getUsedChars());

        $solutions = $this->challengeService->getSolutions($challenge);

        // Reset the user challenge state
        $request->getSession()->remove('challenge');

        return $this->json([
            'data' => [
                'puzzle' => $challenge->getPuzzle()->getText(),
                'used' => $challenge->getUsedChars()->getFrequencies(),
                'score' => $challenge->getScore(),
                'solutions' => $solutions,
            ]
        ]);
    }

    /**
     * These would be much nicer as normalizers for the Challenge instead.
     */
    private function successResponse(Challenge $challenge): JsonResponse{
        $isSolvable = count($this->challengeService->getSolutions($challenge)) > 0;

        return $this->json([
            'data' => [
                'puzzle' => $challenge->getPuzzle()->getText(),
                'used' => $challenge->getUsedChars()->getFrequencies(),
                'score' => $challenge->getScore(),
                'isSolvable' => $isSolvable,
            ]
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
            'data' => [
                'puzzle' => $challenge->getPuzzle()->getText(),
                'used' => $challenge->getUsedChars()->getFrequencies(),
                'score' => $challenge->getScore()
            ]
        ], 400);
    }
}