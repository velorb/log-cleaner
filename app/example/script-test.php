<?php

declare(strict_types=1);

use Velorb\LogCleaner\Cleaner;
use Velorb\LogCleaner\Condition\DateCondition;
use Velorb\LogCleaner\Manager\Iteration\File\FileLogManager;
use Velorb\LogCleaner\Manager\Iteration\File\FileLogProcessor;
use Velorb\LogCleaner\Manager\Iteration\File\Mapper\DefaultFileLogMapper;

require __DIR__ . '/../vendor/autoload.php';

$logFile = __DIR__ . '/messages.log';
$logProcessor = new FileLogProcessor($logFile, new DefaultFileLogMapper());
$condition = new DateCondition(new DateTime('2021-08-17'));
$logManager = new FileLogManager($logProcessor, $condition);
$cleaner = new Cleaner($logManager);
$cleaner->cleanLogs();
