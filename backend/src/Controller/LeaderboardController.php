<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Acme\CountUp\Service\Interface\ChampionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class LeaderboardController extends AbstractController
{
    public function __construct(
        private ChampionServiceInterface $championService,
    ){}

    /**
     * Returns a response of all champions of CountUp
     * (This too, would be nice as a normalizer for Champion)
     */
    public function getChampionsAction(): JsonResponse{
        $formattedChamps = [];
        $champs = $this->championService->getChampions();

        foreach ($champs as $champ) {
            $formattedChamps[] = [
                'name' => $champ->getName(),
                'score' => $champ->getScore()
            ];
        }

        return $this->json([
            'data' => $formattedChamps
        ]);
    }
}