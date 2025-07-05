<?php
declare(strict_types=1);

namespace Acme\CountUp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends AbstractController
{
    public function __construct(
    ){}

    public function getChallenge(Request $request): JsonResponse
    {
        // Serialize and return as json.
        return $this->json(['key' => 'super_secret_key', 'challenge' => 'AAKSJFIENCOAKSJND']);
    }
}