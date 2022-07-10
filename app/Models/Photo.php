<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'photoable_id',
        "photoable_type",
        'src'
    ];
    public function photoable()
    {
        return $this->morphTo();
    }
}
