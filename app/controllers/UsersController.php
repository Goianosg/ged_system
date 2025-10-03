<?php
class UsersController extends Controller
{
    private $userModel;
    private $groupModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        $this->userModel = $this->model('User');
        $this->groupModel = $this->model('Group');
    }

    public function index()
    {
        if (!in_array('view_users_list', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }
        $data = ['users' => $this->userModel->getAll()];
        $this->view('users/index', $data);
    }

    public function create()
    {
        if (!in_array('create_user', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/users');
            exit();
        }

        $data = ['groups' => $this->groupModel->getAll(), 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $foto_path = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $uploadDir = dirname(APPROOT) . '/public/uploads/fotos/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                $fileName = uniqid() . '-' . basename($_FILES['foto']['name']);
                $destination = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
                    $foto_path = '/uploads/fotos/' . $fileName;
                }
            }

            $postData = [
                'nome_usuario'  => trim($_POST['nome_usuario'] ?? ''),
                'email'         => trim($_POST['email'] ?? ''),
                'senha'         => trim($_POST['senha'] ?? ''),
                'confirm_senha' => trim($_POST['confirm_senha'] ?? ''),
                'id_grupo'      => empty($_POST['id_grupo']) ? null : $_POST['id_grupo'],
                'foto_path'     => $foto_path,
            ];

            // Validações
            if (empty($postData['nome_usuario'])) { $data['errors']['nome_usuario'] = 'O nome de usuário é obrigatório.'; } 
            elseif ($this->userModel->findUserByUsername($postData['nome_usuario'])) { $data['errors']['nome_usuario'] = 'Este nome de usuário já existe.'; }

            if (empty($postData['email'])) { $data['errors']['email'] = 'O e-mail é obrigatório.'; } 
            elseif ($this->userModel->findUserByEmail($postData['email'])) { $data['errors']['email'] = 'Este e-mail já está em uso.'; }

            if (empty($postData['senha'])) { $data['errors']['senha'] = 'A senha é obrigatória.'; } 
            elseif (strlen($postData['senha']) < 6) { $data['errors']['senha'] = 'A senha deve ter no mínimo 6 caracteres.'; }

            if ($postData['senha'] !== $postData['confirm_senha']) { $data['errors']['confirm_senha'] = 'As senhas não coincidem.'; }

            if (empty($data['errors'])) {
                $postData['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);
                $newUserId = $this->userModel->create($postData);
                if ($newUserId) {
                    logActivity('USUARIO_CRIADO', 'Criou o novo usuário: ' . htmlspecialchars($postData['nome_usuario']) . ' (ID: ' . $newUserId . ')');
                    header('Location: ' . URLROOT . '/users');
                    exit();
                }
            }
            // Se houver erros, mescla os dados e recarrega a view
            $data = array_merge($data, $postData);
        }
        $this->view('users/create_edit', $data);
    }

    public function edit($id)
    {
        if (!in_array('edit_user', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/users');
            exit();
        }
        
        $user = $this->userModel->getById($id);
        if(!$user) { header('Location: ' . URLROOT . '/users'); exit(); }

        $data = ['user' => $user, 'groups' => $this->groupModel->getAll(), 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $foto_path = $_POST['foto_existente'];
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                // ... (lógica de upload e deleção da foto antiga) ...
            }
            
            $postData = [
                'id' => $id,
                'nome_usuario'  => trim($_POST['nome_usuario'] ?? ''),
                'email'         => trim($_POST['email'] ?? ''),
                'senha'         => trim($_POST['senha'] ?? ''), // Nova senha opcional
                'id_grupo'      => empty($_POST['id_grupo']) ? null : $_POST['id_grupo'],
                'foto_path'     => $foto_path
            ];
            
            // Validações ...
            if ($this->userModel->findUserByUsername($postData['nome_usuario'], $id)) { $data['errors']['nome_usuario'] = 'Este nome de usuário já existe.'; }
            if ($this->userModel->findUserByEmail($postData['email'], $id)) { $data['errors']['email'] = 'Este e-mail já está em uso.'; }
            if (!empty($postData['senha']) && strlen($postData['senha']) < 6) { $data['errors']['senha'] = 'A nova senha deve ter no mínimo 6 caracteres.'; }

            if (empty($data['errors'])) {
                if(!empty($postData['senha'])) {
                    $postData['senha'] = password_hash($postData['senha'], PASSWORD_DEFAULT);
                }
                if ($this->userModel->update($postData)) {
                    header('Location: ' . URLROOT . '/users'); exit();
                }
            }
            // Se houver erros, repopula a view
            $data = array_merge($data, $postData);
            // Garante que o objeto 'user' original não seja perdido
            $data['user'] = $user;
        }
        $this->view('users/create_edit', $data);
    }
    
    public function show($id) {
        if (!in_array('view_user_details', $_SESSION['user_permissions'])) { header('Location: ' . URLROOT . '/users'); exit(); }
        $user = $this->userModel->getById($id);
        if (!$user) { header('Location: ' . URLROOT . '/users'); exit(); }
        $data = ['user' => $user, 'group' => $this->groupModel->getById($user->id_grupo)];
        $this->view('users/show', $data);
    }

    public function delete($id) {
        if (!in_array('delete_user', $_SESSION['user_permissions'])) { header('Location: ' . URLROOT . '/users'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($id == $_SESSION['user_id']) { header('Location: ' . URLROOT . '/users'); exit(); }
            $this->userModel->delete($id);
        }
        header('Location: ' . URLROOT . '/users');
        exit();
    }

    // Método para o próprio usuário editar seu perfil
    public function profile() {
        if (!in_array('edit_own_profile', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }
        
        $currentUser = $this->userModel->getById($_SESSION['user_id']);
        $data = ['user' => $currentUser, 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $foto_path = $_POST['foto_existente'];
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                 $uploadDir = dirname(APPROOT) . '/public/uploads/fotos/';
                 if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                 $fileName = uniqid() . '-' . basename($_FILES['foto']['name']);
                 $destination = $uploadDir . $fileName;
                 if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
                     if (!empty($_POST['foto_existente']) && file_exists(dirname(APPROOT) . '/public' . $_POST['foto_existente'])) {
                         unlink(dirname(APPROOT) . '/public' . $_POST['foto_existente']);
                     }
                     $foto_path = '/uploads/fotos/' . $fileName;
                 }
            }
            
            $updateData = ['id' => $_SESSION['user_id'], 'email' => trim($_POST['email']), 'foto_path' => $foto_path, 'senha' => ''];
            
            if ($this->userModel->findUserByEmail($updateData['email'], $_SESSION['user_id'])) { $data['errors']['email'] = 'Este e-mail já está em uso.'; }
            
            if (!empty($_POST['newPassword'])) {
                if (!password_verify($_POST['senha_atual'], $currentUser->senha)) {
                    $data['errors']['senha_atual'] = 'A senha atual está incorreta.';
                } elseif ($_POST['newPassword'] !== $_POST['confirmPassword']) {
                    $data['errors']['confirmPassword'] = 'A confirmação da nova senha não coincide.';
                } else {
                    $updateData['senha'] = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                }
            }

            if (empty($data['errors'])) {
                if ($this->userModel->updateProfile($updateData)) {
                    $_SESSION['user_foto_path'] = $foto_path;
                    header('Location: ' . URLROOT . '/users/profile'); exit();
                }
            }
            // Se houver erros, repopula a view com os dados e as mensagens
            $data['user'] = (object) array_merge((array)$currentUser, $_POST);
        }
        $this->view('users/profile', $data);
    }
}