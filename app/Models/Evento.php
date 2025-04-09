<?php
// app/Models/Evento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'tipo',
        'asignatura_id',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
