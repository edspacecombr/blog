<?php

namespace App\Controllers;

use App\Resources\MediaResource;
use App\Requests\StoreMediaRequest;
use App\Requests\UpdateAltTextRequest;
use App\Services\MediaService;

class MediaController
{
    private $mediaService;

    public function __construct($pdo)
    {
        $this->mediaService = new MediaService($pdo);
    }

    public function index($pdo)
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 15;

            $result = $this->mediaService->getAll($page, $perPage);

            return $this->response([
                'data' => MediaResource::collection($result['items']),
                'meta' => [
                    'total' => $result['total'],
                    'page' => $result['page'],
                    'per_page' => $result['per_page'],
                    'pages' => $result['pages'],
                ],
                'links' => [
                    'first' => $page > 1 ? "/api/v1/media?page=1&per_page={$perPage}" : null,
                    'prev' => $page > 1 ? "/api/v1/media?page=" . ($page - 1) . "&per_page={$perPage}" : null,
                    'next' => $page < $result['pages'] ? "/api/v1/media?page=" . ($page + 1) . "&per_page={$perPage}" : null,
                    'last' => $page < $result['pages'] ? "/api/v1/media?page=" . $result['pages'] . "&per_page={$perPage}" : null,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show($id, $pdo)
    {
        try {
            $media = $this->mediaService->getById($id);
            return $this->response(['data' => MediaResource::toArray($media)]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function store($pdo)
    {
        try {
            $request = new StoreMediaRequest();

            if (!$request->validate()) {
                return $this->errorResponse('Validação falhou', 422, $request->getErrors());
            }

            $file = $request->getFile();
            $media = $this->mediaService->store($file);

            return $this->response(['data' => MediaResource::toArray($media)], 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function updateAltText($id, $pdo)
    {
        try {
            $request = new UpdateAltTextRequest();

            if (!$request->validate()) {
                return $this->errorResponse('Validação falhou', 422, $request->getErrors());
            }

            $altText = $request->getAltText();
            $media = $this->mediaService->updateAltText($id, $altText);

            return $this->response(['data' => MediaResource::toArray($media)]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function destroy($id, $pdo)
    {
        try {
            $this->mediaService->delete($id);
            return $this->response(['data' => ['message' => 'Mídia deletada com sucesso']], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    protected function response($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function errorResponse($message, $statusCode = 500, $errors = null)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);

        $response = [
            'message' => $message,
            'errors' => $errors ?? [],
        ];

        echo json_encode($response);
        exit;
    }
}
