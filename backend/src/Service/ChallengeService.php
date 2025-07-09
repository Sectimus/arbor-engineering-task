<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Model\Challenge;
use Acme\CountUp\Model\CharFrequency;
use Acme\CountUp\Model\Puzzle;
use Acme\CountUp\Entity\Word;
use Acme\CountUp\Exception\InvalidDictionaryWordException;
use Acme\CountUp\Exception\NotEnoughCharsException;
use Acme\CountUp\Repository\WordRepository;
use Acme\CountUp\Service\Interface\ChallengeServiceInterface;
use Acme\CountUp\Service\Interface\ChampionServiceInterface;
use Acme\CountUp\Service\Interface\PuzzleServiceInterface;
use Acme\CountUp\Service\Interface\WordServiceInterface;
use Doctrine\DBAL\Exception as DBALException;
use Exception;
use InvalidArgumentException;

class ChallengeService implements ChallengeServiceInterface
{
    public function __construct(
        private ChampionServiceInterface $championService,
        private WordRepository $wordRepository
    ){}

    /**
     * @inheritDoc
     */
    public function getSolutions(Challenge $challenge): array{
        $freq = new CharFrequency($challenge->getPuzzle()->getText())
                ->subtractFrequency($challenge->getUsedChars());
        

        return $this->wordRepository->findWordTermByCharFrequency($freq);
    }

    public function createChallenge(Puzzle $puzzle): Challenge { 
        $challenge = new Challenge($puzzle);
        return $challenge;
    }

    public function submitChallenge(Challenge $challenge, string $answer): Challenge { 
        $freq = new CharFrequency($answer);
        // Ensure that we are using a sum of all previous answers in our tally.
        $freq->addFrequency($challenge->getUsedChars());

        // We consider the challenge answered correctly at this point. (there could still be more answers remaining)
        $challenge->addUsedChars(new CharFrequency($answer));

        return $challenge;
    }

    public function completeChallenge(Challenge $challenge, string $name): void { 
        $champion = $this->championService->getChampion($name);

        $this->championService->addScoreToChampion($champion, $challenge->getScore());
        $this->championService->saveChampion($champion);
    }
}