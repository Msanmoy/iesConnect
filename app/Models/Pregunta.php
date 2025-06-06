<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $fillable = [
        'cuestionario_id',
        'nivel',
        'enunciado',
        'puntos',
        'tipo',
        'orden',
    ];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    public function respuestaEstudiante()
    {
        return $this->hasMany(\App\Models\RespuestaEstudiante::class, 'pregunta_id');
    }
}



