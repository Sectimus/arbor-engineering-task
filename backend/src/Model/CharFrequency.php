<?php
declare(strict_types=1);

//TODO move this to models
namespace Acme\CountUp\Entity;

class CharFrequency
{
    /** @var array<string, int> */
    protected array $frequencies;

    public function __construct(
        protected string $inputString, 
    ){
        $this->frequencies = self::createArrayFromString($inputString);
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
     * Subtracts one frequency array with another, returning only the values that were not matched.
     * @param CharFrequency $charFrequency The frequency to subtract
     * @return array<char, int> Any left over characters after the subtraction
     */
    public function subtractFrequency(CharFrequency $charFrequency): array{
        $result = $this->frequencies;
        $subtractFrequencies = $charFrequency->getFrequencies();
        $keysToSearch = array_keys($subtractFrequencies);
        for ($i=0; $i < count($keysToSearch); $i++) { 
            $currentChar = $keysToSearch[$i];

            if(!array_key_exists($currentChar, $result)){
                // No point subtracting from zero
                continue;
            }

            $result[$currentChar] -= $subtractFrequencies[$currentChar];
        }
        return $result;
    }
}