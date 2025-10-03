<?php
// app/controllers/PdfsController.php
class PdfsController extends Controller
{
    private $fileModel;
    private $colaboradorModel;
    private $departamentoModel;
    private $unidadeModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        
        // Carrega todos os models que serão usados neste controller
        $this->fileModel = $this->model('File');
        $this->colaboradorModel = $this->model('Colaborador');
        $this->departamentoModel = $this->model('Departamento');
        $this->unidadeModel = $this->model('Unidade');
    }

    // Exibe a lista geral de PDFs
    public function index()
    {
        // Proteção específica para esta ação
        if (!in_array('view_pdfs_list', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }

        $searchTerm = $_GET['q'] ?? '';
        $files = $this->fileModel->getFilesByGroupId($_SESSION['user_group_id'], $searchTerm);
        $colaboradores = $this->colaboradorModel->getAll();

        $data = [
            'files' => $files,
            'user_permissions' => $_SESSION['user_permissions'],
            'searchTerm' => $searchTerm,
            'colaboradores' => $colaboradores
        ];

        $this->view('pdfs/index', $data);
    }

    // Exibe a página de PDFs de um colaborador específico
    public function colaborador($colaborador_id)
    {
        // Permissão para ver a lista geral também dá acesso a esta página
        if (!in_array('view_pdfs_list', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }

        $colaborador = $this->colaboradorModel->getById($colaborador_id);
        if (!$colaborador) {
            header('Location: ' . URLROOT . '/colaboradores');
            exit();
        }

        $searchTerm = $_GET['q'] ?? '';
        $files = $this->fileModel->getFilesByColaboradorId($colaborador_id, $searchTerm);

        $data = [
            'colaborador' => $colaborador,
            'files' => $files,
            'user_permissions' => $_SESSION['user_permissions'],
            'searchTerm' => $searchTerm,
            'departamento' => $this->departamentoModel->getById($colaborador->departamento_id),
            'unidade' => $this->unidadeModel->getById($colaborador->unidade_id)
        ];

        $this->view('pdfs/colaborador', $data);
    }

    // Processa o upload de um novo arquivo
    public function upload()
    {
        if (!in_array('upload_pdf', $_SESSION['user_permissions'])) {
            die('Acesso negado.');
        }

        $colaboradorId = !empty($_POST['id_colaborador']) ? (int)$_POST['id_colaborador'] : null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $uploadDirRelative = '/uploads/';
            $uploadDirAbsolute = dirname(APPROOT) . '/public' . $uploadDirRelative;
            if (!is_dir($uploadDirAbsolute)) {
                mkdir($uploadDirAbsolute, 0755, true);
            }
            $storedName = uniqid('file_', true) . '.pdf';
            $destination = $uploadDirAbsolute . $storedName;
            $filePathRelative = $uploadDirRelative . $storedName;

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $destination)) {
                $data = [
                    'nome_exibicao' => $_POST['file_name'],
                    'nome_armazenado' => $storedName,
                    'caminho_arquivo' => $filePathRelative, // Salva o caminho relativo
                    'id_usuario_upload' => $_SESSION['user_id'],
                    'id_grupo_pertence' => $_SESSION['user_group_id'],
                    'id_colaborador' => $colaboradorId
                ];
                
                // CORREÇÃO: Estrutura do if para o log
                if ($this->fileModel->addFile($data)) {
                    $logDetails = 'Fez upload do arquivo "' . htmlspecialchars($data['nome_exibicao']) . '"';
                    if ($data['id_colaborador']) {
                        $colaborador = $this->colaboradorModel->getById($data['id_colaborador']);
                        if ($colaborador) {
                            $logDetails .= ' para o colaborador: ' . htmlspecialchars($colaborador->nome_completo);
                        }
                    }
                    logActivity('ARQUIVO_UPLOAD', $logDetails);
                }
            }
        }

        // CORREÇÃO: Redirecionamento movido para o local correto
        $redirectUrl = $colaboradorId ? '/pdfs/colaborador/' . $colaboradorId : '/pdfs';
        header('Location: ' . URLROOT . $redirectUrl);
        exit();
    }

    // Exibe um PDF para visualização
    public function viewPdf($id)
    {
        if (!in_array('view_pdf', $_SESSION['user_permissions'])) {
            die('Acesso negado.');
        }
        $file = $this->fileModel->getFileById($id);
        $filePathAbsolute = dirname(APPROOT) . '/public' . $file->caminho_arquivo;

        if ($file && $file->id_grupo_pertence == $_SESSION['user_group_id'] && file_exists($filePathAbsolute)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file->nome_armazenado . '"');
            readfile($filePathAbsolute);
            exit();
        }
        die("Arquivo não encontrado ou acesso negado.");
    }

    // Deleta um PDF específico
    public function deletePdf($id)
    {
        if (!in_array('delete_pdf', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_colaborador = $_POST['id_colaborador'] ?? null;
            $file = $this->fileModel->getFileById($id);

            if ($file) {
                $filePathAbsolute = dirname(APPROOT) . '/public' . $file->caminho_arquivo;
                // Tenta apagar o arquivo físico do servidor primeiro
                if (file_exists($filePathAbsolute)) {
                    unlink($filePathAbsolute);
                }
                
                // Registra a atividade de exclusão ANTES de apagar do banco
                logActivity('ARQUIVO_DELETADO', 'Deletou o arquivo: "' . htmlspecialchars($file->nome_exibicao) . '"');

                // Apaga o registro do banco de dados
                $this->fileModel->delete($id);
            }

            $redirectUrl = $id_colaborador ? '/pdfs/colaborador/' . $id_colaborador : '/pdfs';
            header('Location: ' . URLROOT . $redirectUrl);
            exit();
        } else {
            header('Location: ' . URLROOT . '/pdfs');
            exit();
        }
    }
}
