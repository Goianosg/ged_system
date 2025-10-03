<?php
// app/controllers/AuthController.php
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * Processa o login do usuário.
     */
    public function login()
    {
        // Se o utilizador já estiver autenticado, redireciona para o dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }

        $data = ['error' => '', 'nome_usuario' => ''];

        // Se o formulário foi enviado (método POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Captura e limpa os dados do formulário
            $nome_usuario = trim($_POST['nome_usuario'] ?? '');
            $password = $_POST['senha'] ?? '';
            $data['nome_usuario'] = $nome_usuario;

            // 2. Validação de campos vazios
            if (empty($nome_usuario) || empty($password)) {
                $data['error'] = 'Por favor, preencha todos os campos.';
                $this->view('auth/login', $data, ['useLayout' => false]);
                return;
            }

            // 3. Busca o usuário no banco de dados
            $user = $this->userModel->findUserByUsername($nome_usuario);
            
            // 4. CORREÇÃO: Verifica se o usuário foi encontrado ANTES de verificar a senha
            $loginSuccess = false;
            if ($user) {
                // Se o utilizador existe, agora verifica a senha
                if (password_verify($password, $user->senha)) {
                    $loginSuccess = true;
                }
            }

            if ($loginSuccess) {
                // Sucesso: Cria a sessão do usuário
                $this->createUserSession($user);

                // Registra a atividade de login
                logActivity('LOGIN_SUCESSO', 'Usuário ' . $user->nome_usuario . ' efetuou login.');

                // Redireciona para o dashboard
                header('Location: ' . URLROOT . '/dashboard');
                exit();
            } else {
                // Falha: Prepara a mensagem de erro genérica e recarrega a view
                $data['error'] = 'Usuário ou senha inválidos.';
                $this->view('auth/login', $data, ['useLayout' => false]);
            }
        } else {
            // Se não for um POST, apenas exibe o formulário de login
            $this->view('auth/login', $data, ['useLayout' => false]);
        }
    }
//  
    private function createUserSession($user)
    {
        // Regenera o ID da sessão para maior segurança
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->nome_usuario;
        $_SESSION['user_group_id'] = $user->id_grupo;
        $_SESSION['user_foto_path'] = $user->foto_path;
        $_SESSION['user_permissions'] = $this->userModel->getPermissionsForUser($user->id);
    }

    /**
     * Encerra a sessão do usuário (logout).
     */
    public function logout()
    {
        // Registra a atividade de logout antes de destruir a sessão
        logActivity('LOGOUT', 'Usuário ' . ($_SESSION['user_name'] ?? '') . ' efetuou logout.');
        
        // Limpa e destrói a sessão
        session_unset();
        session_destroy();
        
        // Redireciona para a página de login
        header('Location: ' . URLROOT . '/auth/login');
        exit();
    }
}