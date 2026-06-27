<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Log;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\RouteLogRepository;
use Framework\Interface\Infrastructure\View\Sistema\Log\RouteLogFormView;

class RouteLogFormController extends FormController
{
    protected bool $gravaLog = false;

    protected function getViewClass(): string
    {
        return RouteLogFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return RouteLogRepository::class;
    }
}
