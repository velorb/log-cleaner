<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Manager\Iteration\File\Mapper;

use Velorb\LogCleaner\Log;

interface IFileLogMapper
{
    public function map(string $log): Log;
}
