<?php

namespace App\Requests;

class UpdateAltTextRequest
{
    private $data;
    private $errors = [];

    public function __construct()
    {
        $this->data = json_decode(file_get_contents('php://input'), true) ?? [];
    }

    public function validate()
    {
        if (empty($this->data) || !is_array($this->data)) {
            $this->errors['alt_text'] = 'Alt text deve ser um objeto JSON com idiomas';
            return false;
        }

        // Validar que cada valor é string
        foreach ($this->data as $lang => $text) {
            if (!is_string($text)) {
                $this->errors[$lang] = 'Alt text deve ser texto';
                return false;
            }
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getAltText()
    {
        return $this->data;
    }
}
