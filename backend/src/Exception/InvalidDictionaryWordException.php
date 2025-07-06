<?php
declare(strict_types=1);

namespace Acme\CountUp\Exception;

use Exception;

class InvalidDictionaryWordException extends ChallengeException
{
    public function __construct(string $message = 'Term does not exist in the dictionary', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}