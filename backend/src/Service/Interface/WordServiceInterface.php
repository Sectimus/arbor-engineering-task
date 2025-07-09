<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

interface WordServiceInterface
{
    /**
     * Returns `true` if the passed string is a valid word in the dictionary
     */
    public function isValidDictionaryWord(string $word): bool;

    /**
     * Returns a true random word.
     */
    public function getRandomWord(): string;
}