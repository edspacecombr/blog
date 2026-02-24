<?php

namespace App\Jobs;

use Exception;

class ConvertMediaFormatsJob
{
    private $pdo;
    private $mediaId;

    public function __construct($pdo, $mediaId)
    {
        $this->pdo = $pdo;
        $this->mediaId = $mediaId;
    }

    public function handle()
    {
        try {
            // Obter mídia do banco
            $sql = 'SELECT * FROM media WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $this->mediaId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Mídia com ID {$this->mediaId} não encontrada");
            }

            // Só processar imagens
            if (!str_starts_with($row['mime_type'], 'image/')) {
                return;
            }

            $originalPath = $_SERVER['DOCUMENT_ROOT'] . '/../' . ltrim($row['original_path'], '/');

            // Garantir que arquivo original existe
            if (!file_exists($originalPath)) {
                throw new Exception("Arquivo original não encontrado: {$originalPath}");
            }

            // Carregar imagem usando GD
            $image = null;
            switch ($row['mime_type']) {
                case 'image/jpeg':
                    $image = \imagecreatefromjpeg($originalPath);
                    break;
                case 'image/png':
                    $image = \imagecreatefrompng($originalPath);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $image = \imagecreatefromwebp($originalPath);
                    }
                    break;
                case 'image/gif':
                    $image = \imagecreatefromgif($originalPath);
                    break;
            }

            if (!$image) {
                throw new Exception("Não foi possível carregar a imagem");
            }

            // Determinar diretório de saída
            $dir = dirname($originalPath);
            $basename = pathinfo($originalPath, PATHINFO_FILENAME);

            // Converter para WebP (se suportado)
            $webpPath = null;
            $webpUrl = null;
            if (function_exists('imagewebp')) {
                $webpPath = $dir . '/' . $basename . '.webp';
                \imagewebp($image, $webpPath, 85);
                $webpUrl = str_replace(
                    $_SERVER['DOCUMENT_ROOT'] . '/../',
                    '/',
                    $webpPath
                );
            }

            // AVIF não é suportado nativamente por GD em PHP 8.3
            $avifPath = null;
            $avifUrl = null;
            // Será implementado via FFmpeg ou biblioteca externa em futuro

            \imagedestroy($image);

            // Atualizar banco de dados
            if ($webpUrl) {
                $updateSql = <<<SQL
                    UPDATE media
                    SET webp_path = :webp_path, avif_path = :avif_path, updated_at = NOW()
                    WHERE id = :id
                SQL;

                $updateStmt = $this->pdo->prepare($updateSql);
                $updateStmt->execute([
                    ':id' => $this->mediaId,
                    ':webp_path' => $webpUrl,
                    ':avif_path' => $avifUrl,
                ]);
            }

        } catch (Exception $e) {
            // Log erro
            error_log("ConvertMediaFormatsJob failed for media ID {$this->mediaId}: " . $e->getMessage());
        }
    }

    public static function dispatch($pdo, $mediaId)
    {
        // Executar imediatamente (sem queue para este MVP)
        try {
            $job = new self($pdo, $mediaId);
            $job->handle();
        } catch (Exception $e) {
            error_log("Failed to dispatch ConvertMediaFormatsJob: " . $e->getMessage());
        }
    }
}

