<?php

namespace App\Services;

use App\Models\Media;
use DateTime;
use Exception;

class MediaService
{
    private $pdo;
    private $storagePath;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->storagePath = $_SERVER['DOCUMENT_ROOT'] . '/../storage/uploads';
        $this->ensureStorageDirectory();
    }

    private function ensureStorageDirectory()
    {
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }

        // Criar estrutura de pastas por mês
        $monthPath = $this->storagePath . '/' . date('Y/m');
        if (!is_dir($monthPath)) {
            mkdir($monthPath, 0755, true);
        }
    }

    public function store($file, $fileName = null)
    {
        if (!isset($file['tmp_name']) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload de arquivo inválido');
        }

        // Validar tamanho
        $maxSize = $file['type'] === 'video/mp4' ? 100 * 1024 * 1024 : 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception('Arquivo excede tamanho máximo permitido');
        }

        // Validar MIME type real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'video/mp4'];
        if (!in_array($mimeType, $allowedMimes)) {
            throw new Exception('Tipo de arquivo não permitido');
        }

        // Gerar nome único (UUID simples)
        $uuid = uniqid('', true);
        $ext = $this->getExtensionFromMime($mimeType);
        $fileName = $uuid . '.' . $ext;

        // Determinar caminho
        $monthPath = date('Y/m');
        $fullPath = "{$monthPath}/{$fileName}";
        $fullDiskPath = $this->storagePath . '/' . $fullPath;

        // Garantir que diretório existe
        if (!is_dir(dirname($fullDiskPath))) {
            mkdir(dirname($fullDiskPath), 0755, true);
        }

        // Mover arquivo
        if (!move_uploaded_file($file['tmp_name'], $fullDiskPath)) {
            throw new Exception('Falha ao salvar arquivo');
        }

        // Salvar no banco de dados
        $media = new Media();
        $media->setFilename($fileName);
        $media->setOriginalPath('/storage/uploads/' . $fullPath);
        $media->setMimeType($mimeType);
        $media->setSize($file['size']);
        $media->setCreatedAt(new DateTime());
        $media->setUpdatedAt(new DateTime());

        $id = $this->saveToDatabase($media);
        $media->setId($id);

        // Disparar job para conversão (simulado aqui - poderia usar queue)
        if (str_starts_with($mimeType, 'image/')) {
            $this->scheduleConversion($id, $fullDiskPath, $mimeType);
        }

        return $media;
    }

    private function getExtensionFromMime($mimeType)
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'video/mp4' => 'mp4',
        ];

        return $map[$mimeType] ?? 'bin';
    }

    private function saveToDatabase(Media $media)
    {
        $sql = <<<SQL
            INSERT INTO media (filename, original_path, mime_type, size, alt_text, created_at, updated_at)
            VALUES (:filename, :original_path, :mime_type, :size, :alt_text, :created_at, :updated_at)
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':filename' => $media->getFilename(),
            ':original_path' => $media->getOriginalPath(),
            ':mime_type' => $media->getMimeType(),
            ':size' => $media->getSize(),
            ':alt_text' => json_encode($media->getAltText()),
            ':created_at' => $media->getCreatedAt()->format('Y-m-d H:i:s'),
            ':updated_at' => $media->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);

        return $this->pdo->lastInsertId('media_id_seq');
    }

    public function getById($id)
    {
        $sql = 'SELECT * FROM media WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception('Mídia não encontrada');
        }

        return $this->rowToModel($row);
    }

    public function getAll($page = 1, $perPage = 15)
    {
        $offset = ($page - 1) * $perPage;

        $sql = <<<SQL
            SELECT * FROM media
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':limit' => $perPage,
            ':offset' => $offset,
        ]);

        $items = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $items[] = $this->rowToModel($row);
        }

        // Total count
        $countSql = 'SELECT COUNT(*) as total FROM media';
        $countStmt = $this->pdo->query($countSql);
        $total = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];

        return [
            'items' => $items,
            'total' => (int)$total,
            'page' => $page,
            'per_page' => $perPage,
            'pages' => ceil($total / $perPage),
        ];
    }

    public function updateAltText($id, $altText)
    {
        $sql = <<<SQL
            UPDATE media
            SET alt_text = :alt_text, updated_at = NOW()
            WHERE id = :id
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':alt_text' => json_encode($altText),
        ]);

        return $this->getById($id);
    }

    public function delete($id)
    {
        $media = $this->getById($id);

        // Deletar arquivo original
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/../' . ltrim($media->getOriginalPath(), '/');
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Deletar WebP se existe
        if ($media->getWebpPath()) {
            $webpPath = $_SERVER['DOCUMENT_ROOT'] . '/../' . ltrim($media->getWebpPath(), '/');
            if (file_exists($webpPath)) {
                unlink($webpPath);
            }
        }

        // Deletar AVIF se existe
        if ($media->getAvifPath()) {
            $avifPath = $_SERVER['DOCUMENT_ROOT'] . '/../' . ltrim($media->getAvifPath(), '/');
            if (file_exists($avifPath)) {
                unlink($avifPath);
            }
        }

        // Deletar do banco
        $sql = 'DELETE FROM media WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    private function rowToModel($row)
    {
        $media = new Media();
        $media->setId($row['id']);
        $media->setFilename($row['filename']);
        $media->setOriginalPath($row['original_path']);
        $media->setWebpPath($row['webp_path']);
        $media->setAvifPath($row['avif_path']);
        $media->setMimeType($row['mime_type']);
        $media->setSize($row['size']);
        $media->setAltText($row['alt_text']);
        $media->setCreatedAt($row['created_at']);
        $media->setUpdatedAt($row['updated_at']);

        return $media;
    }

    private function scheduleConversion($mediaId, $filePath, $mimeType)
    {
        // Disparar job de conversão de formatos
        require_once dirname(__FILE__) . '/../Jobs/ConvertMediaFormatsJob.php';
        \App\Jobs\ConvertMediaFormatsJob::dispatch($this->pdo, $mediaId);
    }
}

