<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoTarea extends Model
{
    protected $table = 'archivos_tarea';

    protected $fillable = [
        'tarea_id',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_archivo',
        'nivel,'
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}
