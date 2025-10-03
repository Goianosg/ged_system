<?php
class User {
    private $db;
    public function __construct() { $this->db = new Database; }

    // --- MÉTODOS DE AUTENTICAÇÃO E PERMISSÃO ---

    public function login($username) {
        $this->db->query('SELECT * FROM usuarios WHERE nome_usuario = :username');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function findUserByUsername($username, $id = 0) {
        $this->db->query('SELECT id FROM usuarios WHERE nome_usuario = :username AND id != :id');
        $this->db->bind(':username', $username);
        $this->db->bind(':id', $id);
        $this->db->execute();
        return ($this->db->rowCount() > 0);
    }
    public function getPermissionsForUser($userId) {
        $this->db->query('SELECT p.chave_permissao FROM permissoes p JOIN grupo_permissoes gp ON p.id = gp.id_permissao JOIN usuarios u ON gp.id_grupo = u.id_grupo WHERE u.id = :userid');
        $this->db->bind(':userid', $userId);
        return array_column($this->db->resultSet(), 'chave_permissao');
    }

    // --- MÉTODOS DE VALIDAÇÃO ---
    public function findUserByEmail($email, $id = 0) {
        $this->db->query('SELECT id FROM usuarios WHERE email = :email AND id != :id');
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $id);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }
    
    // --- MÉTODOS CRUD PARA GERENCIAR USUÁRIOS --- 
    public function create($data) {
        $this->db->query('INSERT INTO usuarios (nome_usuario, email, senha, id_grupo, foto_path) VALUES (:nome_usuario, :email, :senha, :id_grupo, :foto_path)');
        // CORREÇÃO: Nomes dos campos alinhados com o controller
        $this->db->bind(':nome_usuario', $data['nome_usuario']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':senha', $data['senha']);
        $this->db->bind(':id_grupo', $data['id_grupo']);
        $this->db->bind(':foto_path', $data['foto_path']);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getAll() {
        $this->db->query('SELECT u.*, g.nome_grupo FROM usuarios u LEFT JOIN grupos g ON u.id_grupo = g.id ORDER BY u.nome_usuario ASC');
        return $this->db->resultSet();
    }

    /**
     * Busca todos os usuários, ordenados pela data da última mensagem trocada
     * com o usuário logado. Usuários sem mensagens aparecem por último.
     */
    public function getAllSortedByConversation($currentUserId) {
        $this->db->query('
            SELECT 
                u.*,
                (SELECT MAX(m.timestamp) 
                 FROM mensagens m 
                 WHERE (m.id_remetente = u.id AND m.id_destinatario = :current_user_id) 
                    OR (m.id_destinatario = u.id AND m.id_remetente = :current_user_id)
                ) AS last_message_time
            FROM usuarios u
            ORDER BY 
                last_message_time DESC, 
                u.nome_usuario ASC
        ');
        $this->db->bind(':current_user_id', $currentUserId);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT * FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function update($data) {

       if (!empty($data['senha'])) {
            $this->db->query('UPDATE usuarios SET nome_usuario = :nome_usuario, email = :email, senha = :senha, id_grupo = :id_grupo, foto_path = :foto_path WHERE id = :id');            
            $this->db->bind(':senha', $data['senha']);
        } else {

            $this->db->query('UPDATE usuarios SET nome_usuario = :nome_usuario, email = :email, id_grupo = :id_grupo, foto_path = :foto_path WHERE id = :id');
        }
        $this->db->bind(':id', $data['id']);
        //  Nomes dos campos alinhados com o controller
        $this->db->bind(':nome_usuario', $data['nome_usuario']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':id_grupo', $data['id_grupo']);

        $this->db->bind(':foto_path', $data['foto_path'] ?? null);

        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getTotalUsers() {
        $this->db->query('SELECT COUNT(*) as count FROM usuarios');
        return $this->db->single()->count;
    }
}
