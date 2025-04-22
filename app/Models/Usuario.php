<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = ['password'];

    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignatura_usuario', 'usuario_id', 'asignatura_id')->withTimestamps();
    }

    public function asignaturasImpartidas()
    {
        return $this->hasMany(Asignatura::class, 'profesor_id');
    }

    public function tareasCreadas()
    {
        return $this->hasMany(Tarea::class, 'profesor_id');
    }

    public function esEstudiante(): bool
    {
        return $this->rol === 'ESTUDIANTE';
    }
}
