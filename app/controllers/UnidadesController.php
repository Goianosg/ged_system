<?php
class UnidadesController extends Controller {
    private $unidadeModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        if (!in_array('manage_groups_permissions', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard'); exit();
        }
        $this->unidadeModel = $this->model('Unidade');
    }

    public function index() {
        $data = ['unidades' => $this->unidadeModel->getAll()];
        $this->view('unidades/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['nome' => trim($_POST['nome']), 'cidade' => trim($_POST['cidade'])];
            $this->unidadeModel->create($data);
            header('Location: ' . URLROOT . '/unidades'); exit();
        }
        $this->view('unidades/create_edit');
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['id' => $id, 'nome' => trim($_POST['nome']), 'cidade' => trim($_POST['cidade'])];
            $this->unidadeModel->update($data);
            header('Location: ' . URLROOT . '/unidades'); exit();
        }
        $data = ['unidade' => $this->unidadeModel->getById($id)];
        $this->view('unidades/create_edit', $data);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->unidadeModel->delete($id);
            header('Location: ' . URLROOT . '/unidades'); exit();
        }
        header('Location: ' . URLROOT . '/unidades');
    }
}