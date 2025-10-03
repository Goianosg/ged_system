<?php
class DepartamentosController extends Controller {
    private $departamentoModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        if (!in_array('manage_groups_permissions', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard'); exit();
        }
        $this->departamentoModel = $this->model('Departamento');
    }

    public function index() {
        $data = ['departamentos' => $this->departamentoModel->getAll()];
        $this->view('departamentos/index', $data);
    }

    public function create() {
        $data = ['nome' => '', 'error_nome' => ''];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['nome'] = trim($_POST['nome']);
            if (empty($data['nome'])) {
                $data['error_nome'] = 'Por favor, preencha o nome.';
            } elseif ($this->departamentoModel->findDepartamentoByName($data['nome'])) {
                $data['error_nome'] = 'Este nome de departamento já existe.';
            }

            if (empty($data['error_nome'])) {
                $this->departamentoModel->create($data);
                header('Location: ' . URLROOT . '/departamentos'); exit();
            }
        }
        $this->view('departamentos/create_edit', $data);
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataPost = ['id' => $id, 'nome' => trim($_POST['nome'])];
            // Se o nome já existe (excluindo o próprio departamento), retorna para a view com erro
            if ($this->departamentoModel->findDepartamentoByName($dataPost['nome'], $id)) {
                // Busca o departamento original para não perder outros dados na view
                $departamentoOriginal = $this->departamentoModel->getById($id);
                // Mescla os dados originais com o nome que o usuário tentou salvar
                $departamentoComErro = (object)array_merge((array)$departamentoOriginal, $dataPost);
                $data = ['departamento' => $departamentoComErro, 'error_nome' => 'Este nome de departamento já existe.'];
                $this->view('departamentos/create_edit', $data);
                return;
            }
            $this->departamentoModel->update($dataPost);
            header('Location: ' . URLROOT . '/departamentos'); exit();
        }
        $data = ['departamento' => $this->departamentoModel->getById($id)];
        $this->view('departamentos/create_edit', $data);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->departamentoModel->delete($id);
            header('Location: ' . URLROOT . '/departamentos'); exit();
        }
        header('Location: ' . URLROOT . '/departamentos');
    }
}