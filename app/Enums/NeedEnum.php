<?php

namespace App\Enums;

enum NeedEnum: string
{
    case URGENTE = 'urgente';
    case ALTA = 'alta';
    case NORMAL = 'normal';
    case BAIXA = 'baixa';
}