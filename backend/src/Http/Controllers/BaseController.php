<?php

namespace App\Http\Controllers;

use PDO;
use App\Api\Resources\BaseResource;

class BaseController
{
    protected int $statusCode = 200;
    protected array $data = [];
    protected array $errors = [];

    /**
     * Send JSON response
     */
    protected function json($data = null, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        
        $response = [];
        
        if ($data instanceof BaseResource) {
            $response = $data->toArray();
        } elseif (is_array($data)) {
            $response = $data;
        }

        echo json_encode($response);
        exit;
    }

    /**
     * Send error response
     */
    protected function error(string $message, int $status = 400, array $data = []): void
    {
        $response = [
            'error' => $message,
            'status' => $status,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        $this->json($response, $status);
    }

    /**
     * Send success response
     */
    protected function success($data = null, string $message = 'Success', int $status = 200): void
    {
        $response = [];

        if (is_array($data) && isset($data['data']) && isset($data['pagination'])) {
            $response = [
                'data' => $data['data'],
                'meta' => $data['pagination'],
                'links' => $this->buildPaginationLinks($data['pagination']),
            ];
        } else {
            $response = [
                'data' => $data,
                'message' => $message,
            ];
        }

        $this->json($response, $status);
    }

    /**
     * Build pagination links
     */
    private function buildPaginationLinks(array $pagination): array
    {
        $currentPage = $pagination['current_page'];
        $lastPage = $pagination['last_page'];
        $path = $_SERVER['REQUEST_URI'];
        $basePath = explode('?', $path)[0];

        $links = [];
        
        if ($currentPage > 1) {
            $links['first'] = $basePath . '?page=1';
            $links['prev'] = $basePath . '?page=' . ($currentPage - 1);
        }
        
        $links['self'] = $basePath . '?page=' . $currentPage;
        
        if ($currentPage < $lastPage) {
            $links['next'] = $basePath . '?page=' . ($currentPage + 1);
            $links['last'] = $basePath . '?page=' . $lastPage;
        }

        return $links;
    }

    /**
     * Get request method
     */
    protected function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Get request body as JSON
     */
    protected function getJsonBody(): array
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }

    /**
     * Get query parameters
     */
    protected function getQueryParam(string $name, $default = null)
    {
        return $_GET[$name] ?? $default;
    }

    /**
     * Validate required fields
     */
    protected function validateRequired(array $data, array $fields): bool
    {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $this->error("Missing required field: $field", 422, ['field' => $field]);
            }
        }
        return true;
    }
}
