<?php

namespace App\Utils;

use Carbon\Carbon;

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
setlocale(LC_TIME, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');

class DateUtils {

    public const pt_BR = 'dddd, DD \\d\\e MMMM \\d\\e gggg \\a\\s HH:mm';

    public static function getAsDate($date, $format = Carbon::DEFAULT_TO_STRING_FORMAT) {
        return Carbon::createFromFormat($format, date($format, strtotime($date)));
    }

}