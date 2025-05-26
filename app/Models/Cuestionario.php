<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuestionario extends Model
{
    protected $fillable = [
        'tarea_id',
        'fecha_publicacion',
        'fecha_entrega',
        'nota_maxima',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }
}

