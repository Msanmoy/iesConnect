<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TareaEstudiante extends Pivot
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tarea_id',
        'estudiante_id',
        'fase',
        'basico',
        'intermedio',
        'avanzado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fase' => 'integer',
        'basico' => 'double',
        'intermedio' => 'double',
        'avanzado' => 'double',
    ];

    /**
     * Get the task associated with the pivot.
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Get the student associated with the pivot.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}

