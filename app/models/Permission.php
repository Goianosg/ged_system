<?php
class Permission {
    private $db;
    public function __construct() { $this->db = new Database; }

    public function getAll() {
        $this->db->query('SELECT * FROM permissoes ORDER BY id ASC');
        return $this->db->resultSet();
    }
}


class GroupPermission {    private $db;
    public function __construct() { $this->db = new Database; }

    public function getPermissionsByGroupId($groupId) {
        $this->db->query('SELECT p.id, p.nome_permissao, p.chave_permissao FROM grupo_permissoes gp JOIN permissoes p ON gp.id_permissao = p.id WHERE gp.id_grupo = :group_id');
        $this->db->bind(':group_id', $groupId);
        return $this->db->resultSet();
    }

    public function addPermissionToGroup($groupId, $permissionId) {
        $this->db->query('INSERT INTO grupo_permissoes (id_grupo, id_permissao) VALUES (:group_id, :permission_id)');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':permission_id', $permissionId);
        return $this->db->execute();
    }

    public function removePermissionFromGroup($groupId, $permissionId) {
        $this->db->query('DELETE FROM grupo_permissoes WHERE id_grupo = :group_id AND id_permissao = :permission_id');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':permission_id', $permissionId);
        return $this->db->execute();
    }
}

