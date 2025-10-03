<?php
// app/helpers/date_helper.php

function time_elapsed_string($datetime, $full = false)
{
    if (!$datetime) {
        return 'data inválida';
    }

    try {
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $ago = new DateTime($datetime, new DateTimeZone('UTC'));
    } catch (Exception $e) {
        return 'data inválida';
    }

    $diff = $now->diff($ago);

    // Correção: Usar o total de dias para calcular as semanas
    // e não modificar o objeto $diff diretamente.
    $weeks = floor($diff->days / 7);
    $days = $diff->days % 7;

    $string = array(
        'y' => 'ano',
        'm' => 'mês',
        'w' => 'semana',
        'd' => 'dia',
        'h' => 'hora',
        'i' => 'minuto',
        's' => 'segundo',
    );

    // Adiciona as semanas calculadas ao array para verificação
    $string_values = [
        'y' => $diff->y, 'm' => $diff->m, 'w' => $weeks, 'd' => $days,
        'h' => $diff->h, 'i' => $diff->i, 's' => $diff->s
    ];

    foreach ($string as $k => &$v) {
        if ($string_values[$k]) {
            $v = $string_values[$k] . ' ' . $v . ($string_values[$k] > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? 'há ' . implode(', ', $string) : 'agora mesmo';
}