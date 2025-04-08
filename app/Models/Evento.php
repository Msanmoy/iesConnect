<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'tipo', // 'tarea' o 'examen'
        'asignatura_id',
        'usuario_id',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Obtener la asignatura a la que pertenece el evento.
     */
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    /**
     * Obtener el usuario al que pertenece el evento.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
