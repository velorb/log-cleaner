<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Manager\Iteration\File;

use Velorb\LogCleaner\Log;
use Velorb\LogCleaner\Manager\Iteration\File\Mapper\IFileLogMapper;

class FileLogProcessor
{
    /** @var string */
    private $filePath;

    /** @var IFileLogMapper  */
    private $mapper;

    /** @var string */
    private $currentLogString;

    /** @var Log */
    private $currentLog;

    /** @var resource */
    private $fileHandler;

    /** @var resource */
    private $tempHandler;

    /** @var bool */
    private $deleteCurrentLog;

    /** @var bool */
    private $logSaved;

    public function __construct(string $filePath, IFileLogMapper $mapper)
    {
        $this->filePath = $filePath;
        $this->mapper = $mapper;
        $this->fileHandler = fopen($this->filePath, 'rw+');
        $this->tempHandler = fopen('php://temp', 'rw+');
        $this->deleteCurrentLog = false;
        $this->logSaved = false;
    }

    public function getLog(): ?Log
    {
        $this->logSaved = false;
        if ($this->currentLogString && !$this->deleteCurrentLog) {
            $this->logSaved = true;
            fputs($this->tempHandler, $this->currentLogString);
        }

        $currentLogString = fgets($this->fileHandler, 4096);
        if ($currentLogString === false) {
            return null;
        }

        if ($currentLogString === '\n') {
            $this->getLog();
        }

        $this->deleteCurrentLog = false;
        $this->currentLog = $this->mapper->map($currentLogString);
        $this->currentLogString = $currentLogString;

        return $this->currentLog;
    }

    public function deleteCurrentLog(): void
    {
        $this->deleteCurrentLog = true;
    }

    public function saveLogFile(): void
    {
        if (!$this->logSaved && $this->currentLogString && !$this->deleteCurrentLog) {
            fputs($this->tempHandler, $this->currentLogString);
        }
        rewind($this->tempHandler);
        rewind($this->fileHandler);
        ftruncate($this->fileHandler, 0);
        stream_copy_to_stream($this->tempHandler, $this->fileHandler);
        $this->cleanAfterOperation();
    }

    public function cleanAfterOperation(): void
    {
        fclose($this->fileHandler);
        fclose($this->tempHandler);
    }
}
