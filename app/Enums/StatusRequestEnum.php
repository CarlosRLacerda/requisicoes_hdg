<?php

namespace App\Enums;

enum StatusRequestEnum: string
{
    case PENDENTE = 'pendente';
    case APROVADA = 'aprovada';
    case REPROVADA = 'reprovada';
}