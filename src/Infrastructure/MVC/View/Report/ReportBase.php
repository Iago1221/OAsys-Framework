<?php
namespace Framework\Infrastructure\MVC\View\Report;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

use Dompdf\Dompdf;
use Dompdf\Options;

abstract class ReportBase
{
    protected Dompdf $pdf;
    protected array $data;
    protected string $paperSize = 'A4';
    protected string $paperOrientation = 'portrait';
    /** @var array<int, float>|null [x, y, width, height] em pontos (pt), como no Dompdf */
    protected ?array $customPaperSize = null;

    /** Acima disso o Dompdf estoura memória; emite HTML para impressão via navegador. */
    protected const MAX_LINHAS_PDF_DOMPDF = 400;
    protected const HTML5_PARSER_LIMITE_LINHAS = 150;

    public function __construct(array $data = [])
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $this->pdf = new Dompdf($options);
        $this->data = $data;

        $n = $this->getRecordCount();
        if ($n !== null && $n > self::HTML5_PARSER_LIMITE_LINHAS && $n <= self::MAX_LINHAS_PDF_DOMPDF) {
            $this->pdf->getOptions()->setIsHtml5ParserEnabled(false);
        }
    }

    abstract protected function renderTemplate(): string;

    /**
     * Quantidade de registros do relatório. Detecta automaticamente chaves tabulares em {@see $data}.
     * Subclasses podem sobrescrever; retorne null para ignorar fallback HTML por contagem.
     */
    protected function getRecordCount(): ?int
    {
        foreach ([
            'rows',
            'itens',
            'linhas',
            'movimentacoes',
            'titulos',
            'pagamentos',
            'receitas',
            'situacoes',
            'registros',
        ] as $key) {
            if (isset($this->data[$key]) && is_array($this->data[$key])) {
                return count($this->data[$key]);
            }
        }

        return null;
    }

    public function generate(bool $download = false, string $filename = 'relatorio.pdf'): void
    {
        ini_set('memory_limit', '512M');

        $n = $this->getRecordCount();
        if ($n !== null && $n > self::MAX_LINHAS_PDF_DOMPDF) {
            $this->emitirHtmlParaNavegador($filename, $download);
        }

        $html = $this->renderTemplate();
        $this->pdf->loadHtml($html);
        $this->applyPaperSize();
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => $download]);
    }

    protected function applyPaperSize(): void
    {
        if ($this->customPaperSize !== null) {
            $this->pdf->setPaper($this->customPaperSize);
            return;
        }

        $this->pdf->setPaper($this->paperSize, $this->paperOrientation);
    }

    protected function emitirHtmlParaNavegador(string $filename, bool $download = false): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $this->beforeHtmlEmission();

        $html = $this->prepareHtmlForBrowser($this->renderTemplate());
        $htmlFilename = $this->resolveHtmlFilename($filename);

        header('Content-Type: text/html; charset=UTF-8');
        header(
            'Content-Disposition: '
            . ($download ? 'attachment' : 'inline')
            . '; filename="' . $htmlFilename . '"'
        );
        echo $html;
        exit;
    }

    /** Hook para subclasses ajustarem dados/template antes da emissão HTML. */
    protected function beforeHtmlEmission(): void
    {
    }

    protected function prepareHtmlForBrowser(string $html): string
    {
        $printAssets = $this->buildPrintAssets();
        $banner = $this->buildPrintBanner();

        if (stripos($html, '</head>') !== false) {
            $html = str_ireplace('</head>', $printAssets . '</head>', $html);
        } else {
            $html = $printAssets . $html;
        }

        if (preg_match('/<body\b[^>]*>/i', $html, $match, PREG_OFFSET_CAPTURE)) {
            $insertAt = $match[0][1] + strlen($match[0][0]);
            return substr($html, 0, $insertAt) . $banner . substr($html, $insertAt);
        }

        return $banner . $html;
    }

    protected function buildPrintAssets(): string
    {
        $pageRule = $this->buildPageRule();

        return <<<HTML
<style>
{$pageRule}
.report-print-banner {
    margin: 0 0 16px;
    padding: 10px 12px;
    background: #f7f7f7;
    border: 1px solid #ddd;
    font-family: Arial, sans-serif;
    font-size: 12px;
    line-height: 1.4;
}
@media print {
    .report-print-banner { display: none !important; }
}
</style>
HTML;
    }

    protected function buildPageRule(): string
    {
        if ($this->customPaperSize !== null) {
            $widthMm = round($this->customPaperSize[2] * 0.352778, 2);
            $heightMm = round($this->customPaperSize[3] * 0.352778, 2);

            return "@page { size: {$widthMm}mm {$heightMm}mm; margin: 5mm; }";
        }

        return "@page { size: {$this->paperSize} {$this->paperOrientation}; margin: 10mm; }";
    }

    protected function buildPrintBanner(): string
    {
        return '<p class="report-print-banner">Listagem grande exibida em HTML. Use <strong>Ctrl+P</strong> (ou Cmd+P) para imprimir ou escolher <strong>Salvar como PDF</strong> no destino da impressão.</p>';
    }

    protected function resolveHtmlFilename(string $filename): string
    {
        if (preg_match('/\.pdf$/i', $filename)) {
            return preg_replace('/\.pdf$/i', '.html', $filename);
        }

        return $filename . (str_contains($filename, '.') ? '' : '.html');
    }

    protected function renderHtml(string $template, array $vars): string
    {
        extract($vars);
        ob_start();
        include $template;
        return ob_get_clean();
    }
}
