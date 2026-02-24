<?php

namespace App\Models;

use DateTime;

class Media
{
    private $id;
    private $filename;
    private $original_path;
    private $webp_path;
    private $avif_path;
    private $mime_type;
    private $size;
    private $alt_text = [];
    private $created_at;
    private $updated_at;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function getOriginalPath()
    {
        return $this->original_path;
    }

    public function setOriginalPath($path): self
    {
        $this->original_path = $path;
        return $this;
    }

    public function getWebpPath()
    {
        return $this->webp_path;
    }

    public function setWebpPath($path): self
    {
        $this->webp_path = $path;
        return $this;
    }

    public function getAvifPath()
    {
        return $this->avif_path;
    }

    public function setAvifPath($path): self
    {
        $this->avif_path = $path;
        return $this;
    }

    public function getMimeType()
    {
        return $this->mime_type;
    }

    public function setMimeType($type): self
    {
        $this->mime_type = $type;
        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getAltText()
    {
        return $this->alt_text;
    }

    public function setAltText($text): self
    {
        $this->alt_text = is_array($text) ? $text : json_decode($text, true) ?? [];
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($date): self
    {
        $this->created_at = $date instanceof DateTime ? $date : new DateTime($date);
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($date): self
    {
        $this->updated_at = $date instanceof DateTime ? $date : new DateTime($date);
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'original_path' => $this->original_path,
            'webp_path' => $this->webp_path,
            'avif_path' => $this->avif_path,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'alt_text' => $this->alt_text,
            'created_at' => $this->created_at?->format('c'),
            'updated_at' => $this->updated_at?->format('c'),
        ];
    }
}
