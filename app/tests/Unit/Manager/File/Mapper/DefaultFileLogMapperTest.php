<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Tests\Unit\Manager\File\Mapper;

use Velorb\LogCleaner\Exception\LogCleanerException;
use Velorb\LogCleaner\Manager\Iteration\File\Mapper\DefaultFileLogMapper;
use Velorb\LogCleaner\Tests\Helpers\TestCase;

class DefaultFileLogMapperTest extends TestCase
{
    /** @var DefaultFileLogMapper */
    protected $mapper;

    public function setUp(): void
    {
        $this->mapper = new DefaultFileLogMapper();
    }

    /**
     * @throws
     */
    public function testMap_valid_format(): void
    {
        $logFileContent = "[2021-08-15T00:00:00.00+00:00] name.WARNING: log message [] []\n";
        $log = $this->mapper->map($logFileContent);
        $this->assertSame($log->date->format('Y-m-d'), '2021-08-15');
        $this->assertNotEmpty($log->attributes);

        $this->assertEquals("[2021-08-15T00:00:00.00+00:00] name.WARNING: log message [] []\n", $log->attributes['content']);
    }

    /**
     * @throws
     */
    public function testMap_invalid_format():void
    {
        $invalidLog = "2021-08-15T00:00:00.00+00:00 name.WARNING: log message [] []\n";
        $this->expectException(LogCleanerException::class);
        $this->expectExceptionMessageMatches('@^Invalid log format:@');
        $this->mapper->map($invalidLog);
    }

    public function testMap_invalid_date()
    {
        $invalidDateFormat = "[test] name.WARNING: log message [] []\n";
        $this->expectException(LogCleanerException::class);
        $this->expectExceptionMessageMatches('@^Invalid datetime format:@');
        $this->mapper->map($invalidDateFormat);
    }
}
