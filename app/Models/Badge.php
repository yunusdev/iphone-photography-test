<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'number'];

    /**
     * @param int $number
     * @return Model|null
     */
    public static function getNextBadge(int $number): Model|null
    {
        return self::query()->where('number', '>', $number)
            ->orderBy('number')->first();
    }

}
