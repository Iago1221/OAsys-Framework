<?php

namespace Framework\Infrastructure\DB\Persistence\Files;

/**
 * Classe que realiza a leitura de CSV.
 *
 * @since 26/06/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class CsvReader
{
    private $handle;
    private $header;
    private $delimiter;

    public function __construct($filePath, $delimiter = ',')
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new CsvReaderException("Arquivo CSV inválido ou não legível: $filePath");
        }

        $this->delimiter = $delimiter;
        $this->handle = fopen($filePath, 'r');

        if (!$this->handle) {
            throw new CsvReaderException("Erro ao abrir o arquivo CSV.");
        }

        // Lê o cabeçalho
        $this->header = fgetcsv($this->handle, 0, $this->delimiter);

        if (!$this->header || count($this->header) === 0) {
            throw new CsvReaderException("Cabeçalho do CSV vazio ou inválido.");
        }
    }

    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Valida se o cabeçalho contém as colunas esperadas.
     * @param array $expectedColumns - Lista de colunas esperadas (ordem não importa)
     * @param bool $strict - Se true, exige correspondência exata (mesmo número e nomes)
     * @return void
     * @throws CsvReaderException
     */
    public function validateHeader(array $expectedColumns, bool $strict = false)
    {
        $missing = array_diff($expectedColumns, $this->header);

        if (!empty($missing)) {
            throw new CsvReaderException("Colunas ausentes no CSV: " . implode(', ', $missing));
        }

        if ($strict) {
            $extra = array_diff($this->header, $expectedColumns);
            if (!empty($extra)) {
                throw new CsvReaderException("Colunas extras no CSV: " . implode(', ', $extra));
            }
        }
    }

    /**
     * Realiza a leitura da próxima linha do CSV.
     * @return array|false
     */
    public function readRow()
    {
        if (($row = fgetcsv($this->handle, 0, $this->delimiter)) !== false) {
            // Ignora linhas vazias
            if (count(array_filter($row)) === 0) {
                return $this->readRow();
            }

            return array_combine($this->header, $row);
        }
        return false;
    }

    /**
     * Realiza o fechamento do arquivo.
     * @return void
     */
    public function close()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
