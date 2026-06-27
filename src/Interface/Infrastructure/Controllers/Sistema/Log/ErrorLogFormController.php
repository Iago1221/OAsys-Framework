<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Log;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\ErrorLogRepository;
use Framework\Interface\Infrastructure\View\Sistema\Log\ErrorLogFormView;

class ErrorLogFormController extends FormController
{
    protected bool $gravaLog = false;

    protected function getViewClass(): string
    {
        return ErrorLogFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return ErrorLogRepository::class;
    }
}
