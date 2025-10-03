<?php
// app/models/File.php
// Modelo para gerenciar arquivos
class File {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Busca arquivos de um grupo específico, com opção de pesquisa.
     * Usado na página principal de PDFs.
     */
    
    public function getFilesByGroupId($groupId, $searchTerm = '') {
        $sql = 'SELECT 
                    a.*, 
                    u.nome_usuario,
                    c.nome_completo as nome_colaborador 
                FROM arquivos a 
                JOIN usuarios u ON a.id_usuario_upload = u.id
                LEFT JOIN colaboradores c ON a.id_colaborador = c.id
                WHERE a.id_grupo_pertence = :groupid';

        if (!empty($searchTerm)) {
            $sql .= ' AND (a.nome_exibicao LIKE :search_term OR u.nome_usuario LIKE :search_term OR c.nome_completo LIKE :search_term)';
        }
        $sql .= ' ORDER BY a.data_upload DESC';
        
        $this->db->query($sql);
        $this->db->bind(':groupid', $groupId);
        if (!empty($searchTerm)) {
            $this->db->bind(':search_term', '%' . $searchTerm . '%');
        }
        return $this->db->resultSet();
    }





   //models/File.php
    /**
     * Deleta um arquivo do banco de dados.
     * Usado na ação de deletar arquivo.
     */

public function delete($id) {
    $this->db->query('DELETE FROM arquivos WHERE id = :id');
    $this->db->bind(':id', $id);

    if ($this->db->execute()) {
        return true;
    } else {
        return false;
    }
}
    /**
     * Busca arquivos de um colaborador específico, com opção de pesquisa.
     * Usado na página de "PDFs do Colaborador".
     */
    public function getFilesByColaboradorId($colaboradorId, $searchTerm = '') {
        $sql = 'SELECT a.*, u.nome_usuario 
                FROM arquivos a
                JOIN usuarios u ON a.id_usuario_upload = u.id
                WHERE a.id_colaborador = :colaborador_id';
        
        if (!empty($searchTerm)) {
            $sql .= ' AND a.nome_exibicao LIKE :search_term';
        }

        $sql .= ' ORDER BY a.data_upload DESC';
        
        $this->db->query($sql);
        $this->db->bind(':colaborador_id', $colaboradorId);
        
        if (!empty($searchTerm)) {
            $this->db->bind(':search_term', '%' . $searchTerm . '%');
        }
        
        return $this->db->resultSet();
    }

    /**
     * Adiciona um novo registro de arquivo no banco de dados.
     */
    public function addFile($data) {
        $this->db->query('INSERT INTO arquivos (nome_exibicao, nome_armazenado, caminho_arquivo, id_usuario_upload, id_grupo_pertence, id_colaborador) VALUES (:dname, :sname, :path, :userid, :groupid, :colabid)');
        
        $this->db->bind(':dname', $data['nome_exibicao']);
        $this->db->bind(':sname', $data['nome_armazenado']);
        $this->db->bind(':path', $data['caminho_arquivo']);
        $this->db->bind(':userid', $data['id_usuario_upload']);
        $this->db->bind(':groupid', $data['id_grupo_pertence']);
        $this->db->bind(':colabid', $data['id_colaborador']);
        
        return $this->db->execute();
    }

    /**
     * Busca um único arquivo pelo seu ID.
     * Usado para a visualização do PDF.
     */
    public function getFileById($fileId) {
        $this->db->query('SELECT * FROM arquivos WHERE id = :id');
        $this->db->bind(':id', $fileId);
        return $this->db->single();
    }

    /**
     * Conta o número total de arquivos no sistema.
     * Usado no card de relatório da Dashboard.
     */
    public function getTotalFiles() {
        $this->db->query('SELECT COUNT(*) as count FROM arquivos');
        return $this->db->single()->count;
    }
}