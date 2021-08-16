<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Manager\Iteration\File;

use Velorb\LogCleaner\Condition\ILogCondition;
use Velorb\LogCleaner\Manager\ILogManager;

class FileLogManager implements ILogManager
{
    private FileLogProcessor $logProcessor;
    private ILogCondition $checker;

    public function __construct(FileLogProcessor $logProcessor, ILogCondition $checker)
    {
        $this->checker = $checker;
        $this->logProcessor = $logProcessor;
    }

    public function clean(): void
    {
        try {
            while ($log = $this->logProcessor->getLog()) {
                if ($this->checker->shouldLogBeDeleted($log)) {
                    $this->logProcessor->deleteCurrentLog();
                }
            }
            $this->logProcessor->saveLogFile();
        } catch (\Exception $e) {
            $this->logProcessor->cleanAfterOperation();
        }
    }
}
