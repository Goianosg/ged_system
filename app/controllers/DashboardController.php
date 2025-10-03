<?php
// app/controllers/DashboardController.php
class DashboardController extends Controller
{
    // Model para usuários
    private $userModel;
    // Model para arquivos
    private $fileModel;
    // Model para colaboradores
    private $colaboradorModel;

    public function __construct()
    {
        // Verifica se o usuário está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        // Carrega os três models necessários
        $this->userModel = $this->model('User'); // Model de usuários
        $this->fileModel = $this->model('File'); // Model de arquivos
        $this->colaboradorModel = $this->model('Colaborador'); // Model de colaboradores
    }

    // Em app/controllers/DashboardController.php
    // 

    public function index()
    {
        // Busca o total de usuários cadastrados
        $userCount = $this->userModel->getTotalUsers();
        // Busca o total de arquivos enviados
        $fileCount = $this->fileModel->getTotalFiles();
        // Busca o total de colaboradores
        $colaboradorCount = $this->colaboradorModel->getTotal();

        // Obtém informações de espaço em disco do diretório da aplicação
        $diskTotalSpace = disk_total_space(APPROOT); // Espaço total
        $diskFreeSpace = disk_free_space(APPROOT);  // Espaço livre
        $diskUsedSpace = $diskTotalSpace - $diskFreeSpace; // Espaço usado
        $diskUsedPercent = ($diskTotalSpace > 0) ? ($diskUsedSpace / $diskTotalSpace) * 100 : 0; // Porcentagem usada

        // Monta array com todos os dados para a view
        $data = [
            'userCount' => $userCount, // Total de usuários
            'fileCount' => $fileCount, // Total de arquivos
            'colaboradorCount' => $colaboradorCount, // Total de colaboradores

            // Dados do gráfico (em bytes)
            'diskUsed' => $diskUsedSpace, // Espaço usado
            'diskFree' => $diskFreeSpace, // Espaço livre

            // Dados formatados para exibição na view
            'diskTotalFormatted' => formatBytes($diskTotalSpace), // Espaço total formatado
            'diskFreeFormatted' => formatBytes($diskFreeSpace),   // Espaço livre formatado
            'diskUsedFormatted' => formatBytes($diskUsedSpace),   // Espaço usado formatado
            'diskUsedPercent' => round($diskUsedPercent)          // Porcentagem usada arredondada
        ];

        // Renderiza a view do dashboard com os dados
        $this->view('dashboard/index', $data);
    }
}
