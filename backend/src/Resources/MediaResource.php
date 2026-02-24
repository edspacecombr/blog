<?php

namespace App\Resources;

use App\Models\Media;

class MediaResource
{
    public static function toArray(Media $media)
    {
        return [
            'id' => $media->getId(),
            'filename' => $media->getFilename(),
            'url' => $media->getOriginalPath(),
            'webp_url' => $media->getWebpPath(),
            'avif_url' => $media->getAvifPath(),
            'mime_type' => $media->getMimeType(),
            'size' => $media->getSize(),
            'alt_text' => $media->getAltText(),
            'created_at' => $media->getCreatedAt()?->format('c'),
            'updated_at' => $media->getUpdatedAt()?->format('c'),
        ];
    }

    public static function collection($items)
    {
        return array_map(fn($item) => self::toArray($item), $items);
    }
}
