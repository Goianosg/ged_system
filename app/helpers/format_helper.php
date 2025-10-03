<?php
// app/helpers/format_helper.php

/**
 * Formata um número de bytes para um formato legível (KB, MB, GB, etc.)
 *
 * @param int $bytes O número de bytes a ser formatado.
 * @param int $precision O número de casas decimais.
 * @return string O tamanho formatado.
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow)); // 1 << (10 * $pow) é o mesmo que pow(1024, $pow)
    return round($bytes, $precision) . ' ' . $units[$pow];
}