<?php

namespace Framework\Interface\Infrastructure\Controllers\Sistema\Log;

use Framework\Infrastructure\MVC\Controller\FormController;
use Framework\Interface\Infrastructure\Persistence\Sistema\Log\LogRepository;
use Framework\Interface\Infrastructure\View\Sistema\Log\LogFormView;

class LogFormController extends FormController
{
    protected function getViewClass(): string
    {
        return LogFormView::class;
    }

    protected function getRepositoryClass(): string
    {
        return LogRepository::class;
    }
}
