<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'propietario_id',
        'tema_id',
        'eliminado',
        'visible',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'eliminado' => 'boolean',
        'visible' => 'boolean',
    ];

    /**
     * Get the teacher that owns the task.
     */
    public function propietario()
    {
        return $this->belongsTo(Profesor::class, 'propietario_id');
    }

    /**
     * Get the topic that owns the task.
     */
    public function tema()
    {
        return $this->belongsTo(Tema::class);
    }

    /**
     * Get the phases for the task.
     */
    public function fases()
    {
        return $this->hasMany(Fase::class);
    }

    /**
     * The students that belong to the task.
     */
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'tarea_estudiante')
            ->withPivot(['fase', 'basico', 'intermedio', 'avanzado'])
            ->withTimestamps();
    }

    /**
     * Get the route of the task.
     *
     * @return string
     */
    public function getRouteAttribute()
    {
        return "{$this->tema->route} {$this->nombre}";
    }

    /**
     * Get the name of the topic.
     *
     * @return string
     */
    public function getTemaNombreAttribute()
    {
        return $this->tema->nombre;
    }

    /**
     * Get the group and year of the classroom.
     *
     * @return string
     */
    public function getAulaGrupoAnioAttribute()
    {
        return "{$this->tema->aula->grupo} {$this->tema->aula->anio}";
    }

    /**
     * Scope a query to only include non-deleted tasks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('eliminado', false);
    }

    /**
     * Scope a query to only include visible tasks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}

