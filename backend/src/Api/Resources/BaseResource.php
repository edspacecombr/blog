<?php

namespace App\Api\Resources;

use DateTime;

class BaseResource
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Format timestamp to ISO 8601
     */
    protected function formatTimestamp($timestamp): ?string
    {
        if (!$timestamp) {
            return null;
        }

        if ($timestamp instanceof DateTime) {
            return $timestamp->format('c');
        }

        try {
            return (new DateTime($timestamp))->format('c');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Convert array to resource array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * JSON serialize
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
