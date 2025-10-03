<?php
// app/helpers/log_helper.php

function logActivity($acao, $detalhes = '') {
    // Só registra se houver um usuário logado
    if (isset($_SESSION['user_id'])) {
        $db = new Database(); // Cria uma nova instância da classe de banco de dados
        
        $db->query('INSERT INTO atividades_log (id_usuario, acao, detalhes, ip_address) VALUES (:id_usuario, :acao, :detalhes, :ip)');
        
        $db->bind(':id_usuario', $_SESSION['user_id']);
        $db->bind(':acao', $acao);
        $db->bind(':detalhes', $detalhes);
        $db->bind(':ip', $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'); // Adiciona um valor padrão para o IP
        
        $db->execute();
    }
}