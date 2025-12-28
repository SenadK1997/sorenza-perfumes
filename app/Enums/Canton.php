<?php

namespace App\Enums;

enum Canton: string
{
    case SARAJEVO = 'Kanton Sarajevo';
    case TUZLA = 'Tuzlanski';
    case ZENICA_DOBOJ = 'Zeničko-dobojski kanton';
    case HERCEGOVACKO_NERETVANSKI = 'Hercegovačko-neretvanski';
    case UNA_SANA = 'Unsko-sanski';
    case POSAVSKI = 'Posavski';
    case BOSANSKO_PODRINJE = 'Bosansko-podrinjski';
    case SBK = 'Srednjobosanski kanton';
    case ZAPADNOHERCEGOVACKI = 'Zapadnohercegovački';
    case LIVANJSKI = 'Kanton 10';

    public function label(): string
    {
        return $this->value;
    }
}