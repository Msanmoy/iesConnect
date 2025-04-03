<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_archivo',
        'enunciado',
        'fase_id',
    ];

    /**
     * Get the phase that owns the question.
     */
    public function fase()
    {
        return $this->belongsTo(Fase::class);
    }

    /**
     * Get the answers for the question.
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}

