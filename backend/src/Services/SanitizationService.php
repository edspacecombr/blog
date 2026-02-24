<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class SanitizationService
{
    protected $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        
        // Configurar filtros seguros para blog
        $config->set('HTML.Allowed', 'p,br,strong,em,u,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,a,img,pre,code,hr,table,tr,td,th,thead,tbody');
        $config->set('Attr.AllowedFrameborders', array());
        $config->set('HTML.SafeIframe', true);
        
        // Permitir atributos seguros
        $config->set('Attr.AllowedRel', array('nofollow' => true));
        
        // Remover scripts e eventos
        $config->set('HTML.ForbiddenElements', 'script,style,iframe,form,input,button');
        $config->set('Attr.ForbiddenAttr', 'onclick,onerror,onload,onmouseover,onchange');
        
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Sanitizar conteúdo HTML
     *
     * @param string $content
     * @return string
     */
    public function sanitize($content)
    {
        if (is_null($content)) {
            return null;
        }

        return $this->purifier->purify($content);
    }

    /**
     * Sanitizar array de dados
     *
     * @param array $data
     * @param array $html_fields
     * @return array
     */
    public function sanitizeArray($data, $html_fields = [])
    {
        if (!$html_fields) {
            return $data;
        }

        $sanitized = $data;
        foreach ($html_fields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = $this->sanitize($sanitized[$field]);
            }
        }

        return $sanitized;
    }
}
