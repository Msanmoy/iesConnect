<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';
    protected $fillable = [
        'asignatura_id',
        'titulo',
        'descripcion',
        'fecha_limite',
    ];
    protected $casts = [
        'fecha_limite' => 'datetime',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'profesor_id');
    }

    public function archivos()
    {
        return $this->hasMany(ArchivoTarea::class);
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoTarea::class);
    }
}


