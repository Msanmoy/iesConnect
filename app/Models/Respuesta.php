<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $fillable = [
        'pregunta_id',
        'texto',
        'es_correcta',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function respuestasEstudiante()
    {
        return $this->hasMany(RespuestaEstudiante::class);
    }
}


