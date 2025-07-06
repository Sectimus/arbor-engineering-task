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
     * 
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
}