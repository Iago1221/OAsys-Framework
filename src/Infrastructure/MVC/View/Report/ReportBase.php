<?php
namespace Framework\Infrastructure\MVC\View\Report;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

use Dompdf\Dompdf;
use Dompdf\Options;

abstract class ReportBase
{
    protected Dompdf $pdf;
    protected array $data;

    public function __construct(array $data = [])
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // para usar imagens externas
        $this->pdf = new Dompdf($options);
        $this->data = $data;
    }

    abstract protected function renderTemplate(): string;

    public function generate(bool $download = false, string $filename = 'relatorio.pdf'): void
    {
        $html = $this->renderTemplate();
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => $download]);
    }

    protected function renderHtml(string $template, array $vars): string
    {
        extract($vars);
        ob_start();
        include $template;
        return ob_get_clean();
    }
}
