<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Usuario extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'blocked',
        'rol',
    ];

    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'blocked' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellidos}";
    }

    /**
     * Get the estudiante record associated with the user.
     */
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'id');
    }

    /**
     * Get the profesor record associated with the user.
     */
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id');
    }

    /**
     * Determine if the user is a student.
     *
     * @return bool
     */
    public function isEstudiante()
    {
        return $this->rol === 'ESTUDIANTE';
    }

    /**
     * Determine if the user is a teacher.
     *
     * @return bool
     */
    public function isProfesor()
    {
        return $this->rol === 'PROFESOR';
    }

    /**
     * Get the email address that should be used for password reset.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function aulas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Aula::class,
            'aula_estudiante',
            'estudiante_id',
            'aula_id'
        );
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'propietario_id');
    }

}

