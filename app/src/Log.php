<?php

declare(strict_types=1);

namespace Velorb\LogCleaner;

use DateTime;

class Log
{
    /** @var DateTime $date */
    public $date;

    /** @var array */
    public $attributes = [];

    /**
     * @param DateTime $date
     * @param array $attributes
     */
    public function __construct(DateTime $date, $attributes = [])
    {
        $this->date = $date;
        $this->attributes = $attributes;
    }
}
