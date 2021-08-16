<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Condition;

use Velorb\LogCleaner\Log;

interface ILogCondition
{
    public function shouldLogBeDeleted(Log $log): bool;
}
