<?php
// public/index.php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// 1. Carrega a configuração primeiro para definir a constante APPROOT
require_once '../config/config.php';

// 2. Agora, usa a constante APPROOT para todos os outros arquivos do core
// Isso torna o código mais legível e consistente com o resto do sistema.
require_once APPROOT . '/core/Core.php';
require_once APPROOT . '/core/Controller.php';
require_once APPROOT . '/core/Database.php';
require_once APPROOT . '/helpers/format_helper.php';
require_once APPROOT . '/helpers/log_helper.php';
require_once APPROOT . '/helpers/date_helper.php';
require_once APPROOT . '/helpers/date_helper.php';

// Iniciar o Roteador
$init = new Core();