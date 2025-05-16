<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'title',
        'content',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(Usuario::class);
    }
}
