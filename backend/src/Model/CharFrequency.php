<?php
declare(strict_types=1);

namespace Acme\CountUp\Model;

use Acme\CountUp\Service\Interface\FrequencyInterface;

class CharFrequency implements FrequencyInterface
{
    /** @var array<string, int> */
    protected array $frequencies;

    public function __construct(
        protected string $inputString, 
    ){
        $this->frequencies = self::createArrayFromString($inputString);
    }

    public function toString(): string { 
        $freqString = '';
        foreach ($this->frequencies as $char => $count) {
            for ($i=0; $i < $count; $i++) { 
                $freqString .= $char;
            }
        }
        return $freqString;
    }

    /**
     * @return array<string, int>
     */
    public function getFrequencies(): array
    {
        return $this->frequencies;
    }

    /**
     * @param array<string, int> $frequencies
     */
    protected function setFrequencies(array $frequencies): self
    {
        $this->frequencies = $frequencies;
        return $this;
    }

    public function getInputString(): string
    {
        return $this->inputString;
    }

    public function setInputString(string $inputString): self
    {
        $this->inputString = $inputString;
        $this->frequencies = self::createArrayFromString($inputString);
        return $this;
    }

    /**
     * Creates a frequency array which is keyed by each unique character in the string.
     * @return array<string, int>
     */
    private static function createArrayFromString(string $string): array{
        $charFrequenciesArray = [];
        foreach (count_chars($string, 1) as $i => $val) {
            $char = chr($i);
            $charFrequenciesArray[$char] = $val;
        }
        return $charFrequenciesArray;
    }

    /**
     * @inheritDoc
     */
    public function addFrequency(FrequencyInterface $frequency): self{
        $addFrequencies = $frequency->getFrequencies();
        $keysToSearch = array_keys($addFrequencies);
        for ($i=0; $i < count($keysToSearch); $i++) {
            $currentChar = $keysToSearch[$i];

            if(!array_key_exists($currentChar, $this->frequencies)){
                $this->frequencies[$currentChar] = $addFrequencies[$currentChar];
                continue;
            }
            $this->frequencies[$currentChar] += $addFrequencies[$currentChar];
        }


        return $this;
    }

    /**
     * @inheritDoc
     */
    public function subtractFrequency(FrequencyInterface $charFrequency): self{
        $subtractFrequencies = $charFrequency->getFrequencies();
        $keysToSearch = array_keys($subtractFrequencies);
        for ($i=0; $i < count($keysToSearch); $i++) { 
            $currentChar = $keysToSearch[$i];

            if(!array_key_exists($currentChar, $this->frequencies)){
                $this->frequencies[$currentChar] = $subtractFrequencies[$currentChar];
                continue;
            }

            $this->frequencies[$currentChar] -= $subtractFrequencies[$currentChar];
        }

        return $this;
    }
}