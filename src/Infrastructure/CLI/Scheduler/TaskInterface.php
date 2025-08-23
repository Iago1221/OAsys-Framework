<?php

namespace Framework\Infrastructure\CLI\Scheduler;

interface TaskInterface
{
    public function getName(): string;

    public function run(): void;
}
