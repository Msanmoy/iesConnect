<?php

namespace App\Models;

use App\Enums\NivelEnum;
use Illuminate\Database\Eloquent\Model;

class ProgresoTarea extends Model
{
    protected $table = 'progreso_tareas';

    protected $fillable = [
        'tarea_id',
        'usuario_id',
        'nivel_asignado',
        'entregado_sencillo',
        'entregado_intermedio',
        'entregado_avanzado',
    ];

    protected $casts = [
        'nivel_asignado' => NivelEnum::class,
        'entregado_sencillo' => 'boolean',
        'entregado_intermedio' => 'boolean',
        'entregado_avanzado' => 'boolean',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}
