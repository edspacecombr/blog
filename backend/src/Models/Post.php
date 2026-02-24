<?php

namespace App\Models;

class Post
{
    public $id;
    public $slug;
    public $author_id;
    public $category_id;
    public $featured_image;
    public $status;
    public $featured;
    public $view_count;
    public $published_at;
    public $scheduled_at;
    public $created_at;
    public $updated_at;
    public $title;
    public $excerpt;
    public $body;
    public $language_code;

    public static function create($data)
    {
        return new self($data);
    }

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
