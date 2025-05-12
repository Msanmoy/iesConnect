<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'enunciado',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    public function respuestasEstudiante()
    {
        return $this->hasMany(RespuestaEstudiante::class);
    }

}


