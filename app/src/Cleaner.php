<?php

declare(strict_types=1);

namespace Velorb\LogCleaner;

use Velorb\LogCleaner\Manager\ILogManager;

class Cleaner
{
    private ILogManager $logManager;

    public function __construct(ILogManager $logManager)
    {
        $this->logManager = $logManager;
    }

    public function cleanLogs()
    {
        $this->logManager->clean();
    }

}
