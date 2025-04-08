<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asignatura extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'slug',
        'imagen',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generar automáticamente el slug a partir del nombre
        static::creating(function ($asignatura) {
            if (empty($asignatura->slug)) {
                $asignatura->slug = Str::slug($asignatura->nombre);
            }
        });

        // También actualizar el slug si se modifica el nombre
        static::updating(function ($asignatura) {
            if ($asignatura->isDirty('nombre') && empty($asignatura->slug)) {
                $asignatura->slug = Str::slug($asignatura->nombre);
            }
        });
    }

    /**
     * Get the classes for the subject.
     */
    public function clases()
    {
        return $this->hasMany(Clase::class);
    }

    /**
     * Get the tasks for the subject.
     */
    public function tareas()
    {
        return $this->hasManyThrough(Tarea::class, Clase::class, 'asignatura_id', 'tema_id', 'id', 'id')
            ->whereHas('tema', function ($query) {
                $query->whereHas('aula', function ($query) {
                    $query->where('eliminado', false);
                })->where('eliminado', false);
            })
            ->where('eliminado', false);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
