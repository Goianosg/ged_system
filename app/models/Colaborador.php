<?php
// app/models/Colaborador.php
class Colaborador {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Validação Inteligente: Verifica se um email já existe, opcionalmente ignorando um ID.
     * @param string $email O email a ser verificado.
     * @param int $id O ID do colaborador a ser ignorado na verificação (usado na edição).
     * @return bool True se o email já existe, false caso contrário.
     */
    public function findColaboradorByEmail($email, $id = 0) {
        $this->db->query('SELECT id FROM colaboradores WHERE email = :email AND id != :id');
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $id);
        $this->db->execute();
        return ($this->db->rowCount() > 0);
    }

    /**
     * Validação Inteligente: Verifica se um CPF já existe, opcionalmente ignorando um ID.
     */
    public function findColaboradorByCpf($cpf, $id = 0) {
        $this->db->query('SELECT id FROM colaboradores WHERE cpf = :cpf AND id != :id');
        $this->db->bind(':cpf', $cpf);
        $this->db->bind(':id', $id);
        $this->db->execute();
        return ($this->db->rowCount() > 0);
    }

    /**
     * Validação Inteligente: Verifica se um RG já existe, opcionalmente ignorando um ID.
     */
    public function findColaboradorByRg($rg, $id = 0) {
        if (empty($rg)) { return false; } // Não verifica se o RG estiver vazio
        $this->db->query('SELECT id FROM colaboradores WHERE rg = :rg AND id != :id');
        $this->db->bind(':rg', $rg);
        $this->db->bind(':id', $id);
        $this->db->execute();
        return ($this->db->rowCount() > 0);
    }

    // Cria um novo colaborador
    public function create($data) {
        $this->db->query('INSERT INTO colaboradores (nome_completo, email, telefone, cpf, rg, data_nascimento, sexo, endereco, cidade, estado, cep, pais, nacionalidade, estado_civil, numero_dependentes, nivel_educacao, formacao, foto_path, cargo, data_admissao, data_demissao, status, departamento_id, unidade_id, guarda) VALUES (:nome, :email, :tel, :cpf, :rg, :nasc, :sexo, :end, :cid, :est, :cep, :pais, :nac, :civil, :dep, :educ, :form, :foto, :cargo, :admissao, :demissao, :status, :depto_id, :unid_id, :guarda)');
        
        $this->db->bind(':nome', $data['nome_completo']); $this->db->bind(':email', $data['email']);
        $this->db->bind(':tel', $data['telefone']); $this->db->bind(':cpf', $data['cpf']);
        $this->db->bind(':rg', $data['rg']); $this->db->bind(':nasc', $data['data_nascimento']);
        $this->db->bind(':sexo', $data['sexo']); $this->db->bind(':end', $data['endereco']);
        $this->db->bind(':cid', $data['cidade']); $this->db->bind(':est', $data['estado']);
        $this->db->bind(':cep', $data['cep']); $this->db->bind(':pais', $data['pais']);
        $this->db->bind(':nac', $data['nacionalidade']); $this->db->bind(':civil', $data['estado_civil']);
        $this->db->bind(':dep', $data['numero_dependentes']); $this->db->bind(':educ', $data['nivel_educacao']);
        $this->db->bind(':form', $data['formacao']); $this->db->bind(':foto', $data['foto_path']);
        $this->db->bind(':cargo', $data['cargo']); $this->db->bind(':admissao', $data['data_admissao']);
        $this->db->bind(':demissao', $data['data_demissao']); $this->db->bind(':status', $data['status']);
        $this->db->bind(':depto_id', $data['departamento_id']); $this->db->bind(':unid_id', $data['unidade_id']);
        $this->db->bind(':guarda', $data['guarda']);

        return $this->db->execute();
    }

    // Busca todos os colaboradores
    public function getAll($filters = [], $limit = 10, $offset = 0) {
        $sql = 'SELECT c.*, d.nome as depto_nome, u.nome as unidade_nome 
                FROM colaboradores c 
                LEFT JOIN departamentos d ON c.departamento_id = d.id 
                LEFT JOIN unidades u ON c.unidade_id = u.id 
                WHERE 1=1';

        if (!empty($filters['nome'])) { $sql .= ' AND c.nome_completo LIKE :nome'; }
        if (!empty($filters['cpf'])) { $sql .= ' AND c.cpf LIKE :cpf'; }
        if (!empty($filters['data_inicio'])) { $sql .= ' AND c.data_admissao >= :data_inicio'; }
        if (!empty($filters['data_fim'])) { $sql .= ' AND c.data_admissao <= :data_fim'; }

        $sql .= ' ORDER BY c.nome_completo ASC LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);

        if (!empty($filters['nome'])) { $this->db->bind(':nome', '%' . $filters['nome'] . '%'); }
        if (!empty($filters['cpf'])) { $this->db->bind(':cpf', '%' . $filters['cpf'] . '%'); }
        if (!empty($filters['data_inicio'])) { $this->db->bind(':data_inicio', $filters['data_inicio']); }
        if (!empty($filters['data_fim'])) { $this->db->bind(':data_fim', $filters['data_fim']); }
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    public function countAll($filters = []) {
        $sql = 'SELECT COUNT(*) as count FROM colaboradores WHERE 1=1';

        if (!empty($filters['nome'])) { $sql .= ' AND nome_completo LIKE :nome'; }
        if (!empty($filters['cpf'])) { $sql .= ' AND cpf LIKE :cpf'; }
        if (!empty($filters['data_inicio'])) { $sql .= ' AND data_admissao >= :data_inicio'; }
        if (!empty($filters['data_fim'])) { $sql .= ' AND data_admissao <= :data_fim'; }

        $this->db->query($sql);

        if (!empty($filters['nome'])) { $this->db->bind(':nome', '%' . $filters['nome'] . '%'); }
        if (!empty($filters['cpf'])) { $this->db->bind(':cpf', '%' . $filters['cpf'] . '%'); }
        if (!empty($filters['data_inicio'])) { $this->db->bind(':data_inicio', $filters['data_inicio']); }
        if (!empty($filters['data_fim'])) { $this->db->bind(':data_fim', $filters['data_fim']); }

        return $this->db->single()->count;
    }

    // Busca um colaborador pelo ID
    public function getById($id) {
        $this->db->query('SELECT * FROM colaboradores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Atualiza os dados de um colaborador existente
    public function update($data) {
        $this->db->query('UPDATE colaboradores SET nome_completo = :nome, email = :email, telefone = :tel, cpf = :cpf, rg = :rg, data_nascimento = :nasc, sexo = :sexo, endereco = :end, cidade = :cid, estado = :est, cep = :cep, pais = :pais, nacionalidade = :nac, estado_civil = :civil, numero_dependentes = :dep, nivel_educacao = :educ, formacao = :form, foto_path = :foto, cargo = :cargo, data_admissao = :admissao, data_demissao = :data_demissao, status = :status, departamento_id = :depto_id, unidade_id = :unid_id, guarda = :guarda WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nome', $data['nome_completo']); $this->db->bind(':email', $data['email']);
        $this->db->bind(':tel', $data['telefone']); $this->db->bind(':cpf', $data['cpf']);
        $this->db->bind(':rg', $data['rg']); $this->db->bind(':nasc', $data['data_nascimento']);
        $this->db->bind(':sexo', $data['sexo']); $this->db->bind(':end', $data['endereco']);
        $this->db->bind(':cid', $data['cidade']); $this->db->bind(':est', $data['estado']);
        $this->db->bind(':cep', $data['cep']); $this->db->bind(':pais', $data['pais']);
        $this->db->bind(':nac', $data['nacionalidade']); $this->db->bind(':civil', $data['estado_civil']);
        $this->db->bind(':dep', $data['numero_dependentes']); $this->db->bind(':educ', $data['nivel_educacao']);
        $this->db->bind(':form', $data['formacao']); $this->db->bind(':foto', $data['foto_path']);
        $this->db->bind(':cargo', $data['cargo']); $this->db->bind(':admissao', $data['data_admissao']);
        $this->db->bind(':data_demissao', $data['data_demissao']); $this->db->bind(':status', $data['status']);
        $this->db->bind(':depto_id', $data['departamento_id']);
        $this->db->bind(':unid_id', $data['unidade_id']);
        $this->db->bind(':guarda', $data['guarda']);

        return $this->db->execute();
    }
    
    // Conta o total de colaboradores
    public function getTotal() {
        $this->db->query('SELECT COUNT(*) as count FROM colaboradores');
        return $this->db->single()->count;
    }

    // Deleta um colaborador pelo ID
    public function delete($id) {
        $this->db->query('DELETE FROM colaboradores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}