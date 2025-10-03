<?php
class ColaboradoresController extends Controller {
    private $colaboradorModel;
    private $departamentoModel;
    private $unidadeModel;
    private $fileModel;
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        $this->colaboradorModel = $this->model('Colaborador');
        $this->departamentoModel = $this->model('Departamento');
        $this->unidadeModel = $this->model('Unidade');
        $this->fileModel = $this->model('File');
    }

    public function index() {
        if (!in_array('view_collaborators_list', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard'); exit();
        }

        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $filters = [
            'nome' => $_GET['nome'] ?? '',
            'cpf' => $_GET['cpf'] ?? '',
            'data_inicio' => $_GET['data_inicio'] ?? '',
            'data_fim' => $_GET['data_fim'] ?? ''
        ];

        $colaboradores = $this->colaboradorModel->getAll($filters, $limit, $offset);
        $totalColaboradores = $this->colaboradorModel->countAll($filters);

        $data = [
            'colaboradores' => $colaboradores,
            'total' => $totalColaboradores,
            'page' => $page,
            'limit' => $limit,
            'filters' => $filters
        ];

        $this->view('colaboradores/index', $data);
    }

    public function show($id) {
        if (!in_array('view_collaborator_details', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/colaboradores'); exit();
        }
        $colaborador = $this->colaboradorModel->getById($id);
        if (!$colaborador) { header('Location: ' . URLROOT . '/colaboradores'); exit(); }

        $data = [
            'colaborador' => $colaborador,
            'departamento' => $this->departamentoModel->getById($colaborador->departamento_id),
            'unidade' => $this->unidadeModel->getById($colaborador->unidade_id),
            'files' => $this->fileModel->getFilesByColaboradorId($id)
        ];
        $this->view('colaboradores/show', $data);
    }

    public function create() {
        if (!in_array('create_collaborator', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/colaboradores'); exit();
        }
        
        $data = [
            'departamentos' => $this->departamentoModel->getAll(),
            'unidades' => $this->unidadeModel->getAll(),
            'error_email' => '', 'error_cpf' => '', 'error_rg' => '', 'error_guarda' => '', 'error_departamento_id' => '', 'error_unidade_id' => ''
        ];
        
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $foto_path = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $uploadDir = dirname(APPROOT) . '/public/uploads/fotos/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                $fileName = uniqid() . '-' . basename($_FILES['foto']['name']);
                $destination = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
                    $foto_path = '/uploads/fotos/' . $fileName;
                } else { die('Erro ao fazer upload da foto.'); }
            }

            // Sanitiza os dados do POST
            //atualizar essa funcao 
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $postData = [
                'nome_completo' => trim($_POST['nome_completo']), 'email' => trim($_POST['email']),
                'telefone' => trim($_POST['telefone']), 'cpf' => trim($_POST['cpf']),
                'rg' => !empty(trim($_POST['rg'])) ? trim($_POST['rg']) : NULL,
                'data_nascimento' => trim($_POST['data_nascimento']), 'sexo' => $_POST['sexo'],
                'endereco' => trim($_POST['endereco']), 'cidade' => trim($_POST['cidade']),
                'estado' => trim($_POST['estado']), 'cep' => trim($_POST['cep']),
                'pais' => trim($_POST['pais']), 'nacionalidade' => trim($_POST['nacionalidade']),
                'estado_civil' => $_POST['estado_civil'], 'numero_dependentes' => $_POST['numero_dependentes'],
                'nivel_educacao' => $_POST['nivel_educacao'], 'formacao' => trim($_POST['formacao']),
                'foto_path' => $foto_path, 'cargo' => trim($_POST['cargo']),
                'data_admissao' => trim($_POST['data_admissao']), 'data_demissao' => !empty($_POST['data_demissao']) ? trim($_POST['data_demissao']) : NULL,
                'status' => $_POST['status'], 'departamento_id' => $_POST['departamento_id'] ?? null,
                'unidade_id' => $_POST['unidade_id'] ?? null, 'guarda' => trim($_POST['guarda'])
            ];
            }
            
            // Validações
            if (empty($postData['nome_completo'])) { $data['error_nome_completo'] = 'O nome completo é obrigatório.'; }
            if (empty($postData['cpf'])) {
                $data['error_cpf'] = 'O CPF é obrigatório.';
            } elseif (!$this->validateCpf($postData['cpf'])) {
                $data['error_cpf'] = 'O CPF fornecido é inválido.';
            } elseif ($this->colaboradorModel->findColaboradorByCpf($postData['cpf'])) { $data['error_cpf'] = 'Este CPF já está cadastrado.'; }
            if ($postData['rg'] && $this->colaboradorModel->findColaboradorByRg($postData['rg'])) { $data['error_rg'] = 'Este RG já está cadastrado.'; }
            if ($this->colaboradorModel->findColaboradorByEmail($postData['email'])) { $data['error_email'] = 'Este email já está cadastrado.'; }
            if (empty($postData['guarda'])) { $data['error_guarda'] = 'A função guarda é obrigatória.'; }
            if (empty($postData['departamento_id'])) { $data['error_departamento_id'] = 'O departamento é obrigatório.'; }
            if (empty($postData['unidade_id'])) { $data['error_unidade_id'] = 'A unidade é obrigatória.'; }
            if (empty($postData['cargo'])) { $data['error_cargo'] = 'O cargo é obrigatório.'; }

            if (empty($data['error_nome_completo']) && empty($data['error_cpf']) && empty($data['error_rg']) && empty($data['error_email']) && empty($data['error_cargo']) && empty($data['error_guarda']) && empty($data['error_departamento_id']) && empty($data['error_unidade_id'])) {
                if ($this->colaboradorModel->create($postData)) {
                   logActivity('COLABORADOR_CRIADO', 'Criou o colaborador: ' . $postData['nome_completo']);

                    header('Location: ' . URLROOT . '/colaboradores'); exit();
                } else { die('Algo deu errado ao salvar o colaborador.'); }
            } else {
                $data = array_merge($data, $postData);
                $data['departamentos'] = $this->departamentoModel->getAll();
                $data['unidades'] = $this->unidadeModel->getAll();
                $this->view('colaboradores/create_edit', $data);
            }
        } else {
            $this->view('colaboradores/create_edit', $data);
        }
    }
    
    public function edit($id) {
        if (!in_array('edit_collaborator', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/colaboradores'); exit();
        }

        // Busca o colaborador e os dados relacionados antes de processar o POST
        $colaborador = $this->colaboradorModel->getById($id);
        if(!$colaborador) { header('Location: ' . URLROOT . '/colaboradores'); exit(); }

        $data = [
            'colaborador' => $colaborador,
            'departamentos' => $this->departamentoModel->getAll(),
            'unidades' => $this->unidadeModel->getAll()
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $foto_path = $_POST['foto_existente'];
        
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $uploadDir = dirname(APPROOT) . '/public/uploads/fotos/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                $fileName = uniqid() . '-' . basename($_FILES['foto']['name']);
                $destination = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
                    // Se o upload for bem-sucedido, deleta a foto antiga
                    if (!empty($_POST['foto_existente'])) {
                        $oldPhotoFullPath = dirname(APPROOT) . '/public' . $_POST['foto_existente'];
                        if (file_exists($oldPhotoFullPath)) {
                            unlink($oldPhotoFullPath);
                        }
                    }
                    $foto_path = '/uploads/fotos/' . $fileName; // Atualiza o caminho com a nova foto
                } else { die('Erro ao salvar a nova foto.'); }
            }

            // Sanitiza os dados do POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $postData = [
                'id' => $id,
                'nome_completo' => trim($_POST['nome_completo']), 'email' => trim($_POST['email']),
                'telefone' => trim($_POST['telefone']), 'cpf' => trim($_POST['cpf']),
                'rg' => !empty(trim($_POST['rg'])) ? trim($_POST['rg']) : NULL,
                'data_nascimento' => trim($_POST['data_nascimento']), 'sexo' => $_POST['sexo'],
                'endereco' => trim($_POST['endereco']), 'cidade' => trim($_POST['cidade']),
                'estado' => trim($_POST['estado']), 'cep' => trim($_POST['cep']),
                'pais' => trim($_POST['pais']), 'nacionalidade' => trim($_POST['nacionalidade']),
                'estado_civil' => $_POST['estado_civil'], 'numero_dependentes' => $_POST['numero_dependentes'],
                'nivel_educacao' => $_POST['nivel_educacao'], 'formacao' => trim($_POST['formacao']),
                'foto_path' => $foto_path, 'cargo' => trim($_POST['cargo']),
                'data_admissao' => trim($_POST['data_admissao']), 'data_demissao' => !empty($_POST['data_demissao']) ? trim($_POST['data_demissao']) : NULL,
                'status' => $_POST['status'], 'departamento_id' => $_POST['departamento_id'] ?? null,
                'unidade_id' => $_POST['unidade_id'] ?? null, 'guarda' => trim($_POST['guarda'])
            ];
            
            // Validação  para edição
            $data['error_nome_completo'] = empty($postData['nome_completo']) ? 'O nome completo é obrigatório.' : '';
            if (empty($postData['cpf'])) {
                $data['error_cpf'] = 'O CPF é obrigatório.';
            } elseif (!$this->validateCpf($postData['cpf'])) {
                $data['error_cpf'] = 'O CPF fornecido é inválido.';
            } else { $data['error_cpf'] = $this->colaboradorModel->findColaboradorByCpf($postData['cpf'], $id) ? 'Este CPF já está cadastrado em outro colaborador.' : ''; }
            $data['error_rg'] = ($postData['rg'] && $this->colaboradorModel->findColaboradorByRg($postData['rg'], $id)) ? 'Este RG já está cadastrado em outro colaborador.' : '';
            $data['error_email'] = $this->colaboradorModel->findColaboradorByEmail($postData['email'], $id) ? 'Este e-mail já está cadastrado em outro colaborador.' : '';
            $data['error_guarda'] = empty($postData['guarda']) ? 'A função guarda é obrigatória.' : '';
            $data['error_departamento_id'] = empty($postData['departamento_id']) ? 'O departamento é obrigatório.' : '';
            $data['error_unidade_id'] = empty($postData['unidade_id']) ? 'A unidade é obrigatória.' : '';
            $data['error_cargo'] = empty($postData['cargo']) ? 'O cargo é obrigatório.' : '';
            
            if (empty($data['error_nome_completo']) && empty($data['error_cpf']) && empty($data['error_rg']) && empty($data['error_email']) && empty($data['error_cargo']) && empty($data['error_guarda']) && empty($data['error_departamento_id']) && empty($data['error_unidade_id'])) {
                if ($this->colaboradorModel->update($postData)) {
                    header('Location: ' . URLROOT . '/colaboradores/show/' . $id); exit();
                } else { die('Algo deu errado ao atualizar.'); }
            } else {
                 // Recarrega a view com os erros e dados preenchidos
                 // Mescla os dados do POST (que o usuário tentou salvar) com os dados existentes
                 $data['colaborador'] = (object)array_merge((array)$data['colaborador'], $postData);
                 $this->view('colaboradores/create_edit', $data);
            }

        } else {
            $this->view('colaboradores/create_edit', $data);
        }
    }

    public function delete($id){
        if (!in_array('delete_collaborator', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/colaboradores'); exit();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             if($this->colaboradorModel->delete($id)){
                header('Location: ' . URLROOT . '/colaboradores'); exit();
             } else { die('Algo deu errado ao excluir.'); }
        } else {
            header('Location: ' . URLROOT . '/colaboradores'); exit();
        }
    }

    /**
     * Valida um número de CPF.
     * @param string $cpf O CPF para validar (pode conter pontos e traço).
     * @return bool True se o CPF for válido, false caso contrário.
     */
    private function validateCpf($cpf) {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) { return false; }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) { return false; }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}