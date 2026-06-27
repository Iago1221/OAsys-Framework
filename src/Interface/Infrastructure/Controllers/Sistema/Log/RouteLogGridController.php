<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Log;

use Framework\Infrastructure\MVC\Controller\GridController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\RouteLogRepository;
use Framework\Interface\Infrastructure\View\Sistema\Log\RouteLogGridView;

class RouteLogGridController extends GridController
{
    protected function getViewClass(): string
    {
        return RouteLogGridView::class;
    }

    protected function getRepositoryClass(): string
    {
        return RouteLogRepository::class;
    }
}
