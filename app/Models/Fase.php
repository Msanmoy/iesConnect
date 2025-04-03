<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nivel',
        'nombre_archivo',
        'tarea_id',
    ];

    /**
     * Get the task that owns the phase.
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Get the questions for the phase.
     */
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }
}

