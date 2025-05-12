<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaEstudiante extends Model
{
use HasFactory;

protected $fillable = [
'usuario_id',
'pregunta_id',
'respuesta_id',
];

public function pregunta()
{
return $this->belongsTo(Pregunta::class);
}

public function respuesta()
{
return $this->belongsTo(Respuesta::class);
}

public function estudiante()
{
return $this->belongsTo(Usuario::class, 'usuario_id');
}
}
