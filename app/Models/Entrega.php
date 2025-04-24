<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'progreso_tarea_id', 'nivel', 'archivo', 'fecha_entrega'
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
    ];

    public function progreso()
    {
        return $this->belongsTo(ProgresoTarea::class, 'progreso_tarea_id');
    }

    public function getRutaArchivoAttribute()
    {
        return asset('storage/' . $this->archivo);
    }
}
