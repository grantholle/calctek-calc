<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;

    public static int $DECIMALS = 10;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'answer' => 'float',
        ];
    }

    protected function answerFormatted(): Attribute
    {
        return Attribute::get(fn (): string => rtrim(rtrim(number_format($this->answer, static::$DECIMALS), '0'), '.'));
    }
}
