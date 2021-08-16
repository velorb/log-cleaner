<?php

declare(strict_types=1);

namespace Velorb\LogCleaner\Manager;

interface ILogManager
{
    public function clean(): void;
}
