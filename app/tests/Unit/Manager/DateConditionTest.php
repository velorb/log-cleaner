<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Tests\Unit\Manager;

use Velorb\LogCleaner\Log;
use Velorb\LogCleaner\Condition\DateCondition;
use Velorb\LogCleaner\Tests\Helpers\TestCase;

class DateConditionTest extends TestCase
{
    /**
     * @dataProvider dataProvider_testShouldLogBeDeleted
     *
     * @param string $logDate
     * @param string $filterDate
     * @param bool $result
     * @throws \Exception
     */
    public function testShouldLogBeDeleted(string $logDate, string $filterDate, bool $result): void
    {
        $log = $this->createStub(Log::class);
        $log->date = new \DateTime($logDate);

        $dateCondition = new DateCondition(new \DateTime($filterDate));
        $this->assertEquals($result, $dateCondition->shouldLogBeDeleted($log));

    }

    public function dataProvider_testShouldLogBeDeleted(): array
    {
        return [
            ['2020-09-14', '2021-08-15', true],
            ['2021-08-14', '2021-08-15', true],
            ['2021-08-15', '2021-08-15', true],
            ['2021-08-16', '2021-08-15', false],
        ];
    }
}
