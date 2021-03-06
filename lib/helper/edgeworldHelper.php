<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use_helper('I18N');

function __EW($prefix, $term, $suffix = null, $returnAnyWay = true) {
    $s = is_null($suffix) ? strtolower($term) : strtolower($term) . '.' . $suffix;
    $r = __($s, NULL, "ew-{$prefix}");
    return ($r == $s) ? ($returnAnyWay ? $term : '') : $r;
}

/**
 * Преобразование секунд в секунды/минуты/часы/дни/года
 * 
 * @param int $seconds - секунды для преобразования
 *
 * @return array $times:
 *        $times[0] - секунды
 *        $times[1] - минуты
 *        $times[2] - часы
 *        $times[3] - дни
 *        $times[4] - года
 *
 */
function seconds2times($seconds) {
    // значения времени
    $times_values = array('сек.', 'мин.', 'час.', 'д.', 'лет');

    $times = array();

    // считать нули в значениях
    $count_zero = false;

    // количество секунд в году не учитывает високосный год
    // поэтому функция считает что в году 365 дней
    // секунд в минуте|часе|сутках|году
    $periods = array(60, 3600, 86400, 31536000);

    for ($i = 3; $i >= 0; $i--) {
        $period = floor($seconds / $periods[$i]);
        if (($period > 0) || ($period == 0 && $count_zero)) {
            $times[$i + 1] = sprintf('%u %s', $period, $times_values[$i + 1]);
            $seconds -= $period * $periods[$i];

            $count_zero = true;
        }
    }

    $times[0] = sprintf('%u %s', $seconds, $times_values[0]);

    return implode(' ', $times);
}

function serie_is_const($values) {
    $previous = NULL;
    foreach ($values as $value) {
        if (!is_null($previous) && $value !== $previous) {
            return false;
        }
        $previous = $value;
    }
    return true;
}

function serie_first_value($values) {
    foreach($values as $value) {
        if(!is_null($value)) {
            return $value;
        }
    }
    return NULL;
}

function serie_is_boolean($values) {
    foreach($values as $value) {
        if(!is_null($value) && !is_bool($value)) {
            return false;
        }
    }
    return true;    
}
function serie_is_numeric($values) {
    foreach($values as $value) {
        if(!is_null($value) && !is_numeric($value)) {
            return false;
        }
    }
    return true;
}
