<?php

namespace App\Enums;

enum TipoServicioAsignar: string
{
    case SERVICIO = 'Servicio';
    case PRODUCTO = 'Producto';
    case SERVICIO_PRODUCTO = 'Servicio y Producto';

    public static function getTipos(): array
    {
        return [
            self::SERVICIO->value,
            self::PRODUCTO->value,
            self::SERVICIO_PRODUCTO->value,
        ];
    }
}