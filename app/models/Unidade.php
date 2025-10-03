<?php
class Unidade {
    private $db;
    public function __construct() { $this->db = new Database; }

    public function getAll() {
        $this->db->query('SELECT * FROM unidades ORDER BY nome ASC');
        return $this->db->resultSet();
    }

    public function getById($id) {
        if (empty($id)) return null;
        $this->db->query('SELECT * FROM unidades WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query('INSERT INTO unidades (nome, cidade) VALUES (:nome, :cidade)');
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':cidade', $data['cidade']);
        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query('UPDATE unidades SET nome = :nome, cidade = :cidade WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':cidade', $data['cidade']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM unidades WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}