<?php

namespace App\Services;

use Exception;

/**
 * F6.5: Service to call Next.js ISR revalidation webhook
 */
class NextjsRevalidationService
{
    private string $frontendUrl;
    private string $revalidateSecret;

    public function __construct()
    {
        $this->frontendUrl = getenv('NEXT_PUBLIC_API_URL') ?: 'http://localhost:3000';
        $this->revalidateSecret = getenv('REVALIDATE_SECRET') ?: 'secret';
    }

    /**
     * Revalidate post paths on all locales
     */
    public function revalidatePost(string $slug, array $locales = ['pt', 'en', 'es']): bool
    {
        $paths = array_map(
            fn($locale) => "/{$locale}/post/{$slug}",
            $locales
        );

        return $this->revalidate($paths);
    }

    /**
     * Revalidate category paths on all locales
     */
    public function revalidateCategory(string $slug, array $locales = ['pt', 'en', 'es']): bool
    {
        $paths = array_map(
            fn($locale) => "/{$locale}/category/{$slug}",
            $locales
        );

        return $this->revalidate($paths);
    }

    /**
     * Revalidate home page on all locales
     */
    public function revalidateHome(array $locales = ['pt', 'en', 'es']): bool
    {
        $paths = array_map(fn($locale) => "/{$locale}", $locales);
        return $this->revalidate($paths);
    }

    /**
     * Call webhook to revalidate paths
     */
    private function revalidate(array $paths): bool
    {
        try {
            $url = "{$this->frontendUrl}/api/revalidate";
            
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    "X-Revalidate-Secret: {$this->revalidateSecret}",
                ],
                CURLOPT_POSTFIELDS => json_encode(['paths' => $paths]),
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200;
        } catch (Exception $e) {
            error_log("Revalidation failed: " . $e->getMessage());
            return false;
        }
    }
}
