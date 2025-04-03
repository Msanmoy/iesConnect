<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grupo',
        'anio',
        'propietario_id',
        'clase_id',
        'eliminado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'eliminado' => 'boolean',
    ];

    /**
     * Get the class that owns the classroom.
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    /**
     * Get the teacher that owns the classroom.
     */
    public function propietario()
    {
        return $this->belongsTo(Profesor::class, 'propietario_id');
    }

    /**
     * The teachers that belong to the classroom.
     */
    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'aula_profesor');
    }

    /**
     * The students that belong to the classroom.
     */
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'aula_estudiante');
    }

    /**
     * Get the topics for the classroom.
     */
    public function temas()
    {
        return $this->hasMany(Tema::class);
    }

    /**
     * Get the name of the classroom.
     *
     * @return string
     */
    public function getNombreAttribute()
    {
        return "{$this->clase->nombre} {$this->grupo} {$this->anio}";
    }

    /**
     * Scope a query to only include non-deleted classrooms.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('eliminado', false);
    }
}

