<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Asignatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'imagen',
        'usuario_id'
    ];

    public function estudiantes()
    {
        return $this->belongsToMany(Usuario::class, 'asignatura_usuario');
    }

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function recursos()
    {
        return $this->hasMany(Recurso::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'asignatura_usuario', 'asignatura_id', 'usuario_id');
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class);
    }


}
