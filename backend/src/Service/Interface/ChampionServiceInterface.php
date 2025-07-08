<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

use Acme\CountUp\Entity\Champion;

interface ChampionServiceInterface
{

    /**
     * Create or return a champion by the given name.
     */
    public function getChampion(string $name): Champion;

    /**
     * Persist a champion in storage.
     */
    public function saveChampion(Champion $champion): void;

    /**
     * Increment the score of this champion by `$score` amount.
     */
    public function addScoreToChampion(Champion $champion, int $score): void;

    /**
     * Return all champions to display on a leaderboard.
     * @return array<Champion>
     */
    public function getChampions(): array;
}