<?php

namespace Framework\Infrastructure\DB\Persistence\Files;

/**
 * Classe que realiza o upload de certificados digitais.
 *
 * @since 14/04/2025
 * @author Iago Oliveira <prog.iago.oliveira@gmail.com>
 */
class CertificadoUploadHelper
{
    protected $allowedExtensions = ['pfx', 'cer', 'crt', 'pem'];
    protected $allowedMimeTypes = [
        'application/x-pkcs12',         // .pfx
        'application/x-x509-ca-cert',   // .cer, .crt
        'application/pkix-cert',        // .crt
        'application/x-pem-file',       // .pem
        'application/octet-stream'      // fallback genérico
    ];

    protected $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload($inputName)
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Arquivo inválido ou não enviado.'];
        }

        $tmpName = $_FILES[$inputName]['tmp_name'];
        $originalName = $_FILES[$inputName]['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions)) {
            return ['success' => false, 'error' => 'Extensão de arquivo não permitida.'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            return ['success' => false, 'error' => 'Tipo MIME inválido para certificado.'];
        }
        $safeName = sha1(uniqid() . $originalName . time()) . '.' . $extension;
        $destination = $this->uploadDir . $safeName;

        if (!move_uploaded_file($tmpName, $destination)) {
            return ['success' => false, 'error' => 'Falha ao mover o arquivo.'];
        }

        return [
            'success' => true,
            'path' => $destination,
            'filename' => $safeName,
            'original' => $originalName,
            'mime' => $mimeType,
            'extension' => $extension
        ];
    }
}