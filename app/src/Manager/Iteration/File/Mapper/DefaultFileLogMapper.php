<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Manager\Iteration\File\Mapper;

use Velorb\LogCleaner\Exception\LogCleanerException;
use Velorb\LogCleaner\Log;

class DefaultFileLogMapper implements IFileLogMapper
{
    public function map(string $log): Log
    {
        $output = [];
        preg_match('/\[([^\]]*)/', $log, $output);

        if (empty($output[1])) {
            throw new LogCleanerException('Invalid log format: ' . $log);
        }

        $dateTimeString = $output[1];

        try {
            $date = new \DateTime($dateTimeString);
        } catch (\Exception $e) {
            throw new LogCleanerException('Invalid datetime format: ' . $dateTimeString);
        }

        return new Log($date, ['content' => $log]);
    }
}
