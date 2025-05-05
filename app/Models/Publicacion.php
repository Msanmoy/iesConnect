<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publicacion extends Model
{
    use HasFactory;

    protected $table = 'publicaciones';

    protected $fillable = [
        'usuario_id',
        'asignatura_id',
        'contenido',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}
