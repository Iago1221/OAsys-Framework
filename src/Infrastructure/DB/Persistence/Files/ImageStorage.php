<?php

namespace Framework\Infrastructure\DB\Persistence\Files;

use Framework\Infrastructure\Mensagem;

class ImageStorage
{
    private string $basePath;
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /** @var array<string, list<string>> */
    private array $mimePorExtensao = [
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png', 'image/x-png'],
        'gif' => ['image/gif'],
        'webp' => ['image/webp'],
    ];

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

        if (!isset($file['tmp_name'], $file['name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Mensagem('Arquivo de upload inválido.');
        }

        $tmpPath = trim((string) $file['tmp_name']);
        $ext = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $this->allowedExtensions, true)) {
            throw new Mensagem('Tipo de arquivo inválido. Permitidos: ' . implode(', ', $this->allowedExtensions));
        }

        if (!is_readable($tmpPath) || filesize($tmpPath) === 0) {
            throw new Mensagem('Arquivo de imagem vazio ou ilegível.');
        }

        if (!is_dir($this->basePath)) {
            if (!mkdir($this->basePath, 0700, true) && !is_dir($this->basePath)) {
                throw new Mensagem("Erro ao criar diretório: {$this->basePath}");
            }
        }

        $filename = ($prefix ? $prefix . '-' : '') . uniqid() . '.' . $ext;
        $destino = $this->basePath . $filename;

        // --- PROCESSAMENTO PARA LIMITE DE 64KB ---
        $maxSize = 64 * 1024; // 64 KB
        $img = $this->criarImagemGd($tmpPath, $ext);

        $width = imagesx($img);
        $height = imagesy($img);

        // Redimensionamento proporcional até caber no limite de 64KB
        $quality = 90; // para JPG/WebP
        do {
            ob_start();
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($img, null, $quality);
                    break;
                case 'png':
                    // PNG usa compressão 0-9, invertido (0 = sem compressão)
                    imagepng($img, null, 9 - floor($quality / 10));
                    break;
                case 'gif':
                    imagegif($img);
                    break;
                case 'webp':
                    imagewebp($img, null, $quality);
                    break;
            }
            $compressedData = ob_get_clean();

            if (strlen($compressedData) <= $maxSize) {
                break; // já está abaixo do limite
            }

            // Reduz qualidade ou tamanho da imagem
            if ($ext === 'gif') {
                // Para GIFs só podemos redimensionar
                $width = (int)($width * 0.9);
                $height = (int)($height * 0.9);
            } else {
                $quality -= 5; // diminui qualidade
                if ($quality < 10) {
                    // qualidade mínima, agora reduz tamanho físico
                    $width = (int)($width * 0.9);
                    $height = (int)($height * 0.9);
                    $quality = 90; // reset qualidade
                }
            }

            $tmpImg = imagecreatetruecolor($width, $height);
            if ($ext === 'png' || $ext === 'gif') {
                // preserva transparência
                imagecolortransparent($tmpImg, imagecolorallocatealpha($tmpImg, 0, 0, 0, 127));
                imagealphablending($tmpImg, false);
                imagesavealpha($tmpImg, true);
            }
            imagecopyresampled($tmpImg, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
            imagedestroy($img);
            $img = $tmpImg;

        } while (true);

        // Salva o arquivo final
        if (file_put_contents($destino, $compressedData) === false) {
            throw new Mensagem('Falha ao salvar o arquivo de imagem.');
        }

        imagedestroy($img);
        return $filename;
    }

    /**
     * @return \GdImage
     */
    private function criarImagemGd(string $tmpPath, string $ext): \GdImage
    {
        $img = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($tmpPath),
            'png' => @imagecreatefrompng($tmpPath),
            'gif' => @imagecreatefromgif($tmpPath),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmpPath) : false,
            default => false,
        };

        if ($img === false) {
            $blob = file_get_contents($tmpPath);
            if ($blob !== false && $blob !== '') {
                $img = @imagecreatefromstring($blob);
            }
        }

        if ($img === false) {
            $mime = $this->detectarMime($tmpPath);
            $esperados = $this->mimePorExtensao[$ext] ?? [];

            if ($mime !== null && $esperados !== [] && !in_array($mime, $esperados, true)) {
                throw new Mensagem(
                    "O conteúdo do arquivo não é uma imagem .$ext válida (tipo detectado: $mime)."
                );
            }

            throw new Mensagem(
                'Não foi possível processar a imagem. Verifique se o arquivo não está corrompido '
                . 'e se a extensão corresponde ao formato real (PNG, JPEG, GIF ou WebP).'
            );
        }

        return $img;
    }

    private function detectarMime(string $path): ?string
    {
        if (!function_exists('finfo_open')) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($path);

        return is_string($mime) && $mime !== '' ? $mime : null;
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
