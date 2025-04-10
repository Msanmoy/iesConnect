<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
use HasFactory, Notifiable;

protected $fillable = [
'nombre',
'email',
'password',
'rol',
];

protected $hidden = ['password'];

public function asignaturas()
{
return $this->belongsToMany(Asignatura::class, 'asignatura_usuario');
}

public function tareasCreadas()
{
return $this->hasMany(Tarea::class, 'profesor_id');
}
}
