<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

interface WordServiceInterface
{
    public function isValidDictionaryWord(string $word): bool;

    /**
     * @return array<string>
     */
    public function findAnagrams(string $word): array;
}