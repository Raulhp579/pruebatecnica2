<?php

namespace App\Enums;

enum Estado: string
{
    case PENDIENTE = 'Aprobado';
    case EN_PROGRESO = 'En_proceso';
    case COMPLETADA = 'Testing';
    case FINALIZADO = 'Finalizado';

    public static function options(): array
    {
        return [
            self::PENDIENTE->value => 'Aprobado',
            self::EN_PROGRESO->value => 'En Proceso',
            self::COMPLETADA->value => 'Testing',
            self::FINALIZADO->value => 'Finalizado',
        ];
    }
}
