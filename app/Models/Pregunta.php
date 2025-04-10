<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'fase_id'];

    public function fase()
    {
        return $this->belongsTo(Fase::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}


