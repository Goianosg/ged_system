<?php
class ActivityLog {
    private $db;
    public function __construct() { $this->db = new Database; }

    public function getAll() {
        $this->db->query('
            SELECT l.*, u.nome_usuario 
            FROM atividades_log l
            LEFT JOIN usuarios u ON l.id_usuario = u.id
            ORDER BY l.timestamp DESC
        ');
        return $this->db->resultSet();
    }
}