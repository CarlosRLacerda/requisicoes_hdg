<?php

namespace App\Enums;

enum SetorEnum: string
{
    case TI = 'T.I';
    case CONTABILIDADE = 'contabilidade';
    case RH = 'R.H';
    case ALMOXARIFADO = 'almoxarifado';
    case POSTO_1 = 'posto 1';
    case POSTO_2 = 'posto 2';
    case RAIO_X = 'raioX';
    case SUS_FACIL = 'SusFacil';
    case SALA_DOS_MEDICOS = 'sala dos médicos';
    case PEDIATRIA = 'Pediatria';
    case BERCARIO = 'berçário';
    case RECEPCAO = 'recepção';
    case PRONTO_SOCORRO = 'Pronto socorro';
    case FARMACIA = 'farmácia';
    case FARMACIA_SATELITE = 'farmácia satélite';
    case SEGURANCA_DO_TRABALHO = 'Segurança do trabalho';
}