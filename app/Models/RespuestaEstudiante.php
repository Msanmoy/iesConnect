<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaEstudiante extends Model
{
    protected $fillable = [
        'usuario_id',
        'pregunta_id',
        'respuesta_id',
        'respuesta_abierta',
        'nota',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function respuesta()
    {
        return $this->belongsTo(Respuesta::class);
    }
}

