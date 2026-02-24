<?php

namespace App\Requests;

class StoreMediaRequest
{
    private $errors = [];

    public function validate()
    {
        if (!isset($_FILES['file'])) {
            $this->errors['file'] = 'Arquivo é obrigatório';
            return false;
        }

        $file = $_FILES['file'];

        // Validar erro do upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors['file'] = 'Erro no upload do arquivo';
            return false;
        }

        // Validar tamanho
        $maxSize = 10 * 1024 * 1024; // 10MB padrão
        if ($file['size'] > $maxSize) {
            $this->errors['file'] = 'Arquivo excede 10MB';
            return false;
        }

        // Validar MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'video/mp4'];
        if (!in_array($mimeType, $allowedMimes)) {
            $this->errors['file'] = 'Tipo de arquivo não permitido';
            return false;
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFile()
    {
        return $_FILES['file'] ?? null;
    }
}
