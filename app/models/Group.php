<?php
class Group {
    private $db;
    public function __construct() { $this->db = new Database; }

    public function getAll() {
        $this->db->query('SELECT * FROM grupos ORDER BY nome_grupo ASC');
        return $this->db->resultSet();
    }
    public function getById($id) {
        $this->db->query('SELECT * FROM grupos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    public function getGroupPermissions($groupId) {
        $this->db->query('SELECT id_permissao FROM grupo_permissoes WHERE id_grupo = :group_id');
        $this->db->bind(':group_id', $groupId);
        return array_column($this->db->resultSet(), 'id_permissao');
    }
    public function setGroupPermissions($groupId, $permissionsIds) {
        $this->db->query('DELETE FROM grupo_permissoes WHERE id_grupo = :group_id');
        $this->db->bind(':group_id', $groupId);
        $this->db->execute();
        if (!empty($permissionsIds)) {
            $this->db->query('INSERT INTO grupo_permissoes (id_grupo, id_permissao) VALUES (:group_id, :permission_id)');
            foreach ($permissionsIds as $permId) {
                $this->db->bind(':group_id', $groupId);
                $this->db->bind(':permission_id', $permId);
                $this->db->execute();
            }
        }
        return true;
    }
    public function create($data) {
        $this->db->query('INSERT INTO grupos (nome_grupo, descricao) VALUES (:nome, :descricao)');
        $this->db->bind(':nome', $data['nome_grupo']);
        $this->db->bind(':descricao', $data['descricao']);
        return $this->db->execute();
    }
    public function update($data) {
        $this->db->query('UPDATE grupos SET nome_grupo = :nome, descricao = :descricao WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nome', $data['nome_grupo']);
        $this->db->bind(':descricao', $data['descricao']);
        return $this->db->execute();
    }
    public function delete($id) {
        $this->db->query('DELETE FROM grupos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}