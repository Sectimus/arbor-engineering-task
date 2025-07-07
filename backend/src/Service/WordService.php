<?php
declare(strict_types=1);

namespace Acme\CountUp\Service;

use Acme\CountUp\Repository\WordRepository;
use Acme\CountUp\Service\Interface\WordServiceInterface;

class WordService implements WordServiceInterface {
    public function __construct(
        private WordRepository $wordRepository
    ){}

    public function isValidDictionaryWord(string $string): bool { 
        $word = $this->wordRepository->findWordByTerm($string);
        return $word !== null;
    }

    public function findAnagrams(string $word): array{
        $items = $this->wordRepository->findAnagrams($word);
        return $items;
    }
}