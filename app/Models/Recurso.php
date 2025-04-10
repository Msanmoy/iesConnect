<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;

    protected $fillable = ['asignatura_id', 'tipo', 'url'];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }
}


