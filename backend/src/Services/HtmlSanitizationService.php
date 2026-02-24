<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizationService
{
    private $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        
        // Permitir tags seguras
        $config->set('HTML.Allowed', implode(',', [
            'p', 'br', 'strong', 'em', 'i', 'b', 'u',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li',
            'a[href|target]',
            'img[src|alt|width|height]',
            'blockquote',
            'pre', 'code',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th',
            'div', 'span',
        ]));

        // Configurar comportamento
        $config->set('HTML.TargetBlank', true);
        $config->set('URL.AllowedProtocols', ['http', 'https', 'mailto']);
        $config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_parent', '_top']);

        // Cache configuration
        $config->set('Cache.SerializerPath', sys_get_temp_dir());

        $this->purifier = new HTMLPurifier($config);
    }

    public function sanitize($html)
    {
        if (empty($html)) {
            return '';
        }

        return $this->purifier->purify($html);
    }

    /**
     * Sanitizar múltiplos campos HTML simultaneamente
     */
    public function sanitizeMultiple($data)
    {
        $htmlFields = ['content', 'excerpt', 'body', 'description'];
        
        foreach ($htmlFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = $this->sanitize($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Extrair texto simples (sem tags HTML)
     */
    public function stripTags($html)
    {
        return strip_tags($html);
    }

    /**
     * Verificar se HTML contém scripts maliciosos
     */
    public function isClean($html)
    {
        // Verificar tags perigosas conhecidas
        $dangerousTags = ['script', 'iframe', 'object', 'embed', 'applet'];
        
        foreach ($dangerousTags as $tag) {
            if (stripos($html, "<{$tag}") !== false || stripos($html, "</{$tag}>") !== false) {
                return false;
            }
        }

        // Verificar handlers de evento perigosos
        if (preg_match('/on\w+\s*=/i', $html)) {
            return false;
        }

        return true;
    }
}
