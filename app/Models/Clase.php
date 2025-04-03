<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asignatura_id',
        'curso_id',
    ];

    /**
     * Get the subject that owns the class.
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    /**
     * Get the course that owns the class.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * The teachers that belong to the class.
     */
    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'clase_profesor');
    }

    /**
     * Get the classrooms for the class.
     */
    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }

    /**
     * Get the name of the class.
     *
     * @return string
     */
    public function getNombreAttribute()
    {
        return "{$this->curso->nombre} {$this->asignatura->nombre}";
    }
}

