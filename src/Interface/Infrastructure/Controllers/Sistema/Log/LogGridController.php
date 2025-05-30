<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Log;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\LogRepository;
use Framework\Interface\Infrastructure\View\Sistema\Log\LogGridView;

class LogGridController extends GridController
{
    protected function getViewClass(): string
    {
        return LogGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return LogRepository::class;
    }
}