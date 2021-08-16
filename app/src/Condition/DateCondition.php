<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Condition;

use DateTime;
use Velorb\LogCleaner\Log;

class DateCondition implements ILogCondition
{
    private DateTime $deleteLogsOlderThan;

    public function __construct(DateTime $deleteLogsTill)
    {
        $this->deleteLogsOlderThan = $deleteLogsTill;
    }

    public function shouldLogBeDeleted(Log $log): bool
    {
        return $log->date <= $this->deleteLogsOlderThan;
    }
}
