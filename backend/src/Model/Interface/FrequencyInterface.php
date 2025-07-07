<?php
declare(strict_types=1);

namespace Acme\CountUp\Service\Interface;

interface FrequencyInterface
{
    /**
     * A frequency is a `string` character with the value of how "frequent" the character appears in a given string.
     * @return array<string, int>
     */
    public function getFrequencies(): array;

    /**
     * Adds one frequency array with another
     * @param self $frequency The frequency to add
     */
    public function addFrequency(self $frequency): self;

    /**
     * Subtracts one frequency array with another
     * @param self $frequency The frequency to subtract
     */
    public function subtractFrequency(self $frequency): self;
}