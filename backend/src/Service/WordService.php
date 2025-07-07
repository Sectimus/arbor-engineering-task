<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Entity\CharFrequency;
use Acme\CountUp\Repository\WordRepository;

class WordService {
    public function __construct(private WordRepository $wordRepository)
    {
        
    }
    public function scrabbled(string $string): void{
        $freq = new CharFrequency($string);
        $items = $this->wordRepository->scrabbleCheck($freq->getFrequencies());
        $asd=1;
    }
}