<?php

namespace App\Models;

class Menu extends Model
{
    protected string $table = 'menus';
    protected array $fillable = ['name', 'slug'];
}
