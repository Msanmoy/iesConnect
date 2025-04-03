<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Usuario
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'blocked',
        'aula',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($estudiante) {
            $estudiante->rol = 'ESTUDIANTE';
        });
    }

    /**
     * The classrooms that belong to the student.
     */
    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'aula_estudiante');
    }

    /**
     * The tasks that belong to the student.
     */
    public function tareas()
    {
        return $this->belongsToMany(Tarea::class, 'tarea_estudiante')
            ->withPivot(['fase', 'basico', 'intermedio', 'avanzado'])
            ->withTimestamps();
    }
}

