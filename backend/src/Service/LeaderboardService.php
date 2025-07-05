<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\Track;

class LeaderboardService
{
    public function __construct()
    {}

    /**
     * Retrieves all tracks from the repository.
     *
     * @return Track[] An array of Track entities.
     */
    public function getAllTracks(): array
    {
        return [];
    }
}