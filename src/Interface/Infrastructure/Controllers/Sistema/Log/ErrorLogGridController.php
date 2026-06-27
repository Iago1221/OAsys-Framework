<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Log;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\ErrorLogRepository;
use Framework\Interface\Infrastructure\View\Sistema\Log\ErrorLogGridView;

class ErrorLogGridController extends GridController
{
    protected function getViewClass(): string
    {
        return ErrorLogGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ErrorLogRepository::class;
    }
}
