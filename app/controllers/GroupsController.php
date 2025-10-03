<?php
class GroupsController extends Controller {
    private $groupModel;
    private $permissionModel;

    public function __construct() {
        // Apenas verifica se o usuário está logado
        if (!isset($_SESSION['user_id'])) { 
            header('Location: ' . URLROOT . '/auth/login'); 
            exit(); 
        }
        
        // Carrega os models necessários
        $this->groupModel = $this->model('Group');
        $this->permissionModel = $this->model('Permission');
    }

    // Lista todos os grupos
    public function index() {
        // Permissão para ver a lista de grupos
        if (!in_array('manage_groups_permissions', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard'); 
            exit();
        }
        $data = ['groups' => $this->groupModel->getAll()];
        $this->view('groups/index', $data);
    }

    // Gerencia as permissões de um grupo
    public function permissions($id) {
        if (!in_array('manage_groups_permissions', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard'); 
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $permissionsIds = $_POST['permissions'] ?? [];
            if ($this->groupModel->setGroupPermissions($id, $permissionsIds)) {
                header('Location: ' . URLROOT . '/groups'); 
                exit();
            } else { 
                die('Erro ao salvar permissões.'); 
            }
        } else {
            $group = $this->groupModel->getById($id);
            if(!$group){ header('Location: ' . URLROOT . '/groups'); exit(); }
            
            $data = [
                'group' => $group,
                'all_permissions' => $this->permissionModel->getAll(),
                'group_permissions' => $this->groupModel->getGroupPermissions($id)
            ];
            $this->view('groups/permissions', $data);
        }
    }

    // Exibe o formulário e cria um novo grupo
    public function create() {
        // Usando uma permissão específica para criar (você precisa adicioná-la no banco)
        if (!in_array('create_group', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/groups'); 
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nome_grupo' => trim($_POST['nome_grupo'] ?? ''), 
                'descricao' => trim($_POST['descricao'] ?? ''),
                'error' => ''
            ];

            if (empty($data['nome_grupo'])) {
                $data['error'] = 'O nome do grupo é obrigatório.';
                $this->view('groups/create_edit', $data);
            } else {
                $this->groupModel->create($data);
                header('Location: ' . URLROOT . '/groups'); 
                exit();
            }
        } else {
            // Garante que a view de criação tenha a variável de erro
            $this->view('groups/create_edit', ['error' => '']);
        }
    }

    // Exibe o formulário e edita o nome/descrição de um grupo
    public function edit($id) {
        if (!in_array('manage_groups_permissions', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/groups'); 
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id, 
                'nome_grupo' => trim($_POST['nome_grupo']), 
                'descricao' => trim($_POST['descricao'])
            ];
            $this->groupModel->update($data);
            header('Location: ' . URLROOT . '/groups'); 
            exit();
        } else {
            $data = ['group' => $this->groupModel->getById($id)];
            $this->view('groups/create_edit', $data);
        }
    }

    // Deleta um grupo
    public function delete($id) {
        if (!in_array('manage_groups_permissions', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/groups'); 
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lógica para verificar se o grupo não está em uso antes de deletar (recomendado)
            if($this->groupModel->delete($id)){
                header('Location: ' . URLROOT . '/groups'); 
                exit();
            } else { 
                die('Algo deu errado ao excluir.'); 
            }
        } else {
            header('Location: ' . URLROOT . '/groups'); 
            exit();
        }
    }
}