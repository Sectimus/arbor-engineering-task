<?php
declare(strict_types=1);

namespace Acme\CountUp\Model;

use Acme\CountUp\Model\Interface\FrequencyInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * Helper class for performing calculations on the frequency of characters within a string.
 * @implements IteratorAggregate<string, int>
 */
class CharFrequency implements FrequencyInterface, IteratorAggregate
{
    private const ALPHABET = [
        'a','b','c','d','e','f','g','h','i',
        'j','k','l','m','n','o','p','q','r',
        's','t','u','v','w','x','y','z'
    ];

    /** @var array<string, int> */
    protected array $frequencies;

    public function __construct(
        string $inputString, 
        bool $padding = false
    ){
        $this->frequencies = self::createArrayFromString($inputString, $padding);
    }

    public function getIterator(): Traversable { 
        return new ArrayIterator($this->frequencies);
    }

    public function toString(): string { 
        $freqString = '';
        foreach ($this as $char => $count) {
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

    /**
     * Creates a frequency array which is keyed by each unique character in the string.
     * @return array<string, int>
     */
    private static function createArrayFromString(string $string, bool $padding): array{
        $charFrequenciesArray = [];
        if($padding){
            foreach (self::ALPHABET as $letter) {
                $charFrequenciesArray[$letter] = 0;
            }
        }

        foreach (count_chars($string, 1) as $i => $val) {
            $char = chr($i);
            $charFrequenciesArray[$char] = $val;
        }
        return $charFrequenciesArray;
    }

    /**
     * @inheritDoc
     */
    public function addFrequency(FrequencyInterface $charFrequency): self{
        $addFrequencies = $charFrequency->getFrequencies();
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

    /**
     * @inheritDoc
     */
    public function containsFrequency(FrequencyInterface $charFrequency): bool { 
        $subFrequencies = $charFrequency->getFrequencies();
        $keysToSearch = array_keys($subFrequencies);
        for ($i=0; $i < count($keysToSearch); $i++) { 
            $currentChar = $keysToSearch[$i];


            if(!array_key_exists($currentChar, $this->frequencies)){
                return false;
            }
        }

        return true;
    }
}