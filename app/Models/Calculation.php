<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'answer' => 'float',
        ];
    }
}
