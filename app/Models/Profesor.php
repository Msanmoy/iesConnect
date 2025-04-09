<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Usuario
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($profesor) {
            $profesor->rol = 'PROFESOR';
        });
    }

    /**
     * The classes that belong to the teacher.
     */
    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'clase_profesor');
    }

    /**
     * The classrooms owned by the teacher.
     */
    public function aulasOwned()
    {
        return $this->hasMany(Aula::class, 'propietario_id');
    }
}

