<?php

namespace App\Enums;


enum NivelEnum: string
{
    case SENCILLO = 'sencillo';
    case INTERMEDIO = 'intermedio';
    case AVANZADO = 'avanzado';

    public function siguiente(): ?self
    {
        return match ($this) {
            self::SENCILLO => self::INTERMEDIO,
            self::INTERMEDIO => self::AVANZADO,
            self::AVANZADO => null,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}

