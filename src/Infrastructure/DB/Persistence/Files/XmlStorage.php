<?php

namespace Framework\Infrastructure\DB\Persistence\Files;

use Framework\Infrastructure\Exceptions\Mensagem;

class XmlStorage
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/') . '/';
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function salvarXml(string $filename, string $conteudo)
    {
        $dir = dirname($this->basePath);

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0700, true) && !is_dir($dir)) {
                throw new Mensagem("Erro ao criar diretório: $dir");
            }
        }

        return file_put_contents($this->basePath . $filename, $conteudo);
    }

    public function lerXml(string $filename)
    {
        return file_exists($this->basePath . $filename) ? file_get_contents($this->basePath . $filename) : false;
    }

    public function existeXml(string $filename): bool
    {
        return file_exists($this->basePath . $filename);
    }

    public static function lerXmlPeloPath(string $path)
    {
        return file_exists($path) ? file_get_contents($path) : false;
    }
}
