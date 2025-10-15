<?php

namespace Framework\Infrastructure\DB\Persistence\Files;

use Framework\Infrastructure\Mensagem;

class ImageStorage
{
    private string $basePath;
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function __construct(string $cliente)
    {
        $this->basePath = "/var/www/html/storage/image/$cliente/";
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Salva uma imagem enviada via formulário (campo $_FILES).
     *
     * @param array $file O array $_FILES['campo'].
     * @param string|null $prefix Um prefixo opcional para o nome do arquivo.
     * @return string Nome final do arquivo salvo.
     * @throws Mensagem
     */
    public function salvarUpload(array $file, ?string $prefix = null): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Mensagem('Erro no upload da imagem.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $this->allowedExtensions, true)) {
            throw new Mensagem('Tipo de arquivo inválido. Permitidos: ' . implode(', ', $this->allowedExtensions));
        }

        if (!is_dir($this->basePath)) {
            if (!mkdir($this->basePath, 0700, true) && !is_dir($this->basePath)) {
                throw new Mensagem("Erro ao criar diretório: {$this->basePath}");
            }
        }

        $filename = ($prefix ? $prefix . '-' : '') . uniqid() . '.' . $ext;
        $destino = $this->basePath . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Mensagem('Falha ao salvar o arquivo de imagem.');
        }

        return $filename;
    }

    /**
     * Lê uma imagem (retorna o conteúdo binário).
     */
    public function lerImagem(string $filename): string|false
    {
        $path = $this->basePath . $filename;
        return file_exists($path) ? file_get_contents($path) : false;
    }

    /**
     * Verifica se a imagem existe.
     */
    public function existeImagem(string $filename): bool
    {
        return file_exists($this->basePath . $filename);
    }

    /**
     * Exclui uma imagem do disco.
     */
    public function excluirImagem(string $filename): bool
    {
        $path = $this->basePath . $filename;
        return file_exists($path) ? unlink($path) : false;
    }

    /**
     * Retorna o caminho público da imagem (para usar em <img src="...">)
     */
    public static function getPublicPath(string $cliente, string $filename): string
    {
        return "/var/www/html/storage/image/$cliente/$filename";
    }

    /**
     * Retorna o conteúdo de uma imagem diretamente por caminho completo.
     */
    public static function lerImagemPeloPath(string $path): string|false
    {
        return file_exists($path) ? file_get_contents($path) : false;
    }
}
