<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Tests\Helpers;

class SampleProvider
{

    /**
     * @param string[] $dates
     */
    public function createLogFile(array $dates): string
    {
        $logPattern = "[%sT00:00:00.00+00:00] name.WARNING: log message [] []\n";
        $fileName = tempnam('/tmp', 'log');

        $fh = fopen($fileName, 'rw+');

        foreach ($dates as $date) {
            fputs($fh, sprintf($logPattern, $date));
        }

        fclose($fh);

        return $fileName;
    }
}
