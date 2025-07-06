<?php
declare(strict_types=1);

namespace Acme\CountUp\Exception;

use Exception;

class NotEnoughCharsException extends ChallengeException
{
    public function __construct(string $message = 'There are insufficient characters available', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}