<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Champion;
use Acme\CountUp\Repository\ChampionRepository;
use Acme\CountUp\Service\Interface\ChampionServiceInterface;

class ChampionService implements ChampionServiceInterface
{
    public function __construct(
        private ChampionRepository $championRepository
    ){}

    /**
     * Create a brand new champion
     */
    public function createChampion(string $name, int $score = 0): Champion
    {
        $champion = new Champion();

        $champion->setName($name);
        $champion->setScore($score);

        return $champion;
    }

    /**
     * @inheritDoc
     */
    public function saveChampion(Champion $champion): void
    {
        $this->championRepository->save($champion, true);
    }

    /**
     * @inheritDoc
     */
    public function getChampion(string $name): Champion
    {
        $champion = $this->championRepository->findByName($name);

        if ($champion === null){
            $champion = $this->createChampion($name);
        }

        return $champion;
    }

    /**
     * @inheritDoc
     */
    public function addScoreToChampion(Champion $champion, int $score): void{
        $champion->addScore($score);
    }
}