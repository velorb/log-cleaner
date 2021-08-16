<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Tests\Unit;


use DateTime;
use DateTimeZone;
use Velorb\LogCleaner\Cleaner;
use Velorb\LogCleaner\Condition\DateCondition;
use Velorb\LogCleaner\Manager\Iteration\File\FileLogManager;
use Velorb\LogCleaner\Manager\Iteration\File\FileLogProcessor;
use Velorb\LogCleaner\Manager\Iteration\File\Mapper\DefaultFileLogMapper;
use Velorb\LogCleaner\Tests\Helpers\TestCase;

class CleanerTest extends TestCase
{
    /**
     * @dataProvider dataProvider_testCleanLogsFromFile
     * @throws \Exception
     */
    public function testCleanLogs_from_file_equals_or_older_than_date(string $olderThan, array $dates, array $resultShouldContains): void
    {
        $logFile = self::$sampleProvider->createLogFile($dates);

        $logProcessor = new FileLogProcessor($logFile, new DefaultFileLogMapper());
        $condition = new DateCondition(new DateTime($olderThan));
        $logManager = new FileLogManager($logProcessor, $condition);
        $cleaner = new Cleaner($logManager);
        $cleaner->cleanLogs();

        $logContent = file_get_contents($logFile);

        foreach ($resultShouldContains as $resultShouldContain) {
            list($dateToSearch, $expectedCount) =  $resultShouldContain;
            $this->assertEquals($expectedCount, substr_count($logContent, $dateToSearch));
        }

        if (empty($resultShouldContains)) {
            $this->assertEmpty($logContent);
        }

        unlink($logFile);
    }

    public function dataProvider_testCleanLogsFromFile(): array
    {
        return [
            // no logs
            [
                '2021-08-15',
                [
                ],
                []
            ],
            // one log - remove (dates are equal)
            [
                '2021-08-15',
                [
                    '2021-08-15'
                ],
                []
            ],
            // one log - do not remove (date is not older than condition)
            [
                '2021-08-15',
                [
                    '2021-08-16'
                ],
                [
                    ['2021-08-16', 1]
                ],
            ],
            [
                '2021-08-15',
                [
                    '2021-08-16',
                    '2021-08-16',
                    '2021-08-17',
                ],
                [
                    ['2021-08-16', 2],
                    ['2021-08-17', 1],
                ]
            ],
            [
                '2021-08-15',
                [
                    '2021-08-14',
                    '2021-08-15',
                    '2021-08-16',
                    '2021-08-17',
                    '2021-08-12'
                ],
                [
                    ['2021-08-16', 1],
                    ['2021-08-17', 1],
                ]
            ]
        ];
    }
}
