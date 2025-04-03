<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'aula_id',
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
     * Get the classroom that owns the topic.
     */
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    /**
     * Get the tasks for the topic.
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Get the route of the topic.
     *
     * @return string
     */
    public function getRouteAttribute()
    {
        return "{$this->aula->nombre} {$this->nombre}";
    }

    /**
     * Scope a query to only include non-deleted topics.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('eliminado', false);
    }
}

