<?php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ged_db');

define('APPROOT', dirname(__DIR__) . '/app');

// URL Dinâmica: Detecta o protocolo (http/https), o host (localhost ou IP) e o caminho para a pasta public.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
// Remove o nome do script (index.php) para obter o diretório base da URL
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Garante que não haja uma barra no final, a menos que seja a raiz
$base_url = ($script_dir === '/') ? '' : rtrim($script_dir, '/');
define('URLROOT', $protocol . $host . $base_url);

define('SITENAME', 'ARQZUT'); // Nome do site
define('APPVERSION', '1.0.0');