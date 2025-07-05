<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Champion;

class LeaderboardService
{
    public function __construct()
    {}

    /**
     * Retrieves all tracks from the repository.
     *
     * @return Champion[] An array of leaderboard entries.
     */
    public function getLeadboard(): array
    {
        return [];
    }
}