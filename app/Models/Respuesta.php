<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'respuesta',
        'correcta',
        'pregunta_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'correcta' => 'boolean',
    ];

    /**
     * Get the question that owns the answer.
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
}

