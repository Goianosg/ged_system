<?php
class Departamento {
    private $db;
    public function __construct() { $this->db = new Database; }

    public function findDepartamentoByName($name, $id = 0) {
        $this->db->query('SELECT id FROM departamentos WHERE nome = :nome AND id != :id');
        $this->db->bind(':nome', $name);
        $this->db->bind(':id', $id);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    public function getAll() {
        $this->db->query('SELECT * FROM departamentos ORDER BY nome ASC');
        return $this->db->resultSet();
    }

    public function getById($id) {
        if (empty($id)) return null;
        $this->db->query('SELECT * FROM departamentos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query('INSERT INTO departamentos (nome) VALUES (:nome)');
        $this->db->bind(':nome', $data['nome']);
        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query('UPDATE departamentos SET nome = :nome WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nome', $data['nome']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM departamentos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}