<?php
class Message {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function sendMessage($data) {
        $this->db->query('INSERT INTO mensagens (id_remetente, id_destinatario, conteudo) VALUES (:remetente, :destinatario, :conteudo)');
        $this->db->bind(':remetente', $data['id_remetente']);
        $this->db->bind(':destinatario', $data['id_destinatario']);
        $this->db->bind(':conteudo', $data['conteudo']);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getMessagesBetweenUsers($userId1, $userId2) {
        $this->db->query('SELECT m.*, u.nome_usuario as nome_remetente, u.foto_path as foto_remetente FROM mensagens m JOIN usuarios u ON m.id_remetente = u.id WHERE (id_remetente = :user1 AND id_destinatario = :user2) OR (id_remetente = :user2 AND id_destinatario = :user1) ORDER BY timestamp ASC');
        $this->db->bind(':user1', $userId1);
        $this->db->bind(':user2', $userId2);
        return $this->db->resultSet();
    }
    
    public function getRecentConversations($userId, $limit = 5) {
        $this->db->query("
            SELECT 
                p.partner_id,
                u.nome_usuario AS partner_name,
                u.foto_path AS partner_foto_path,
                m.conteudo AS last_message,
                m.timestamp
            FROM (
                SELECT
                    CASE
                        WHEN id_remetente = :user_id THEN id_destinatario
                        ELSE id_remetente
                    END AS partner_id,
                    MAX(id) AS max_message_id
                FROM mensagens
                WHERE id_remetente = :user_id OR id_destinatario = :user_id
                GROUP BY partner_id
            ) AS p
            JOIN mensagens m ON p.max_message_id = m.id
            JOIN usuarios u ON p.partner_id = u.id
            ORDER BY m.timestamp DESC
            LIMIT :limit
        ");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function countUnreadMessages($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM mensagens WHERE id_destinatario = :user_id AND lido = 0');
        $this->db->bind(':user_id', $userId);
        return $this->db->single()->count;
    }

    public function markConversationAsRead($userId, $partnerId) {
        $this->db->query('UPDATE mensagens SET lido = 1 WHERE id_destinatario = :user_id AND id_remetente = :partner_id AND lido = 0');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':partner_id', $partnerId);
        return $this->db->execute();
    }

    public function getMessageById($id) {
        $this->db->query('SELECT * FROM mensagens WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function deleteMessage($id) {
        $this->db->query('DELETE FROM mensagens WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function clearConversation($userId1, $userId2) {
        $this->db->query('DELETE FROM mensagens WHERE (id_remetente = :user1 AND id_destinatario = :user2) OR (id_remetente = :user2 AND id_destinatario = :user1)');
        $this->db->bind(':user1', $userId1); $this->db->bind(':user2', $userId2); return $this->db->execute();
    }

    /**
     * Obtém a contagem de mensagens não lidas para um usuário,
     * agrupadas por remetente, e o total.
     */
    public function getUnreadMessageCounts($userId) {
        $this->db->query('
            SELECT id_remetente, COUNT(id) as unread_count
            FROM mensagens
            WHERE id_destinatario = :user_id AND lido = 0
            GROUP BY id_remetente
        ');
        $this->db->bind(':user_id', $userId);
        $perUser = $this->db->resultSet();

        $this->db->query('SELECT COUNT(id) as total_unread FROM mensagens WHERE id_destinatario = :user_id AND lido = 0');
        $this->db->bind(':user_id', $userId);
        $total = $this->db->single();

        return ['total' => $total->total_unread ?? 0, 'by_user' => $perUser];
    }
}
