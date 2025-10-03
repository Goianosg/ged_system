<?php
class ChatPageController extends Controller {
    private $messageModel;
    private $userModel;
    private $fileModel;
    private $colaboradorModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('User');
        $this->fileModel = $this->model('File');
        $this->colaboradorModel = $this->model('Colaborador');
    }

    /**
     * Página principal do chat, mostra a lista de usuários.
     */
    public function index() {
        // O acesso direto a esta página não é permitido, pois o chat é um widget.
        // Redireciona para o dashboard.
        header('Location: ' . URLROOT . '/dashboard');
        exit();
    }

    /**
     * Renderiza o widget de chat para ser injetado em outras páginas.
     * Este método não deve incluir header/footer.
     */
    public function widget() {
        header('Content-Type: application/json');
        try {
            $currentUserId = (int)$_SESSION['user_id'];
            $unreadCounts = $this->messageModel->getUnreadMessageCounts($currentUserId);
            $data = [
                'users' => $this->userModel->getAllSortedByConversation($currentUserId),
                'messages' => [],
                'current_partner_id' => null,
                'partner' => null,
                'partner_name' => 'Chat',
                'unread_counts' => $unreadCounts['by_user'] ?? []
            ];
    
            // Captura o HTML da view em uma variável
            ob_start();
            $this->view('messages/index', $data, ['useLayout' => false]);
            $html = ob_get_clean();
    
            echo json_encode(['success' => true, 'html' => $html]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Carrega uma conversa específica com um usuário (retorna JSON).
     */
    public function conversation($partner_id) {
        header('Content-Type: application/json');
        try {
            $partner_id = (int)$partner_id;
            $currentUserId = (int)$_SESSION['user_id'];
    
            // Marca as mensagens como lidas ao abrir a conversa
            $this->messageModel->markConversationAsRead($currentUserId, $partner_id);
            $unreadCounts = $this->messageModel->getUnreadMessageCounts($currentUserId);
    
            $data = [
                'users' => $this->userModel->getAll(),
                'messages' => $this->messageModel->getMessagesBetweenUsers($currentUserId, $partner_id),
                'current_partner_id' => $partner_id,
                'partner_name' => $this->userModel->getById($partner_id)->nome_usuario ?? 'Desconhecido',
                'unread_counts' => $unreadCounts['by_user'] ?? []
            ];
    
            ob_start();
            $this->view('messages/index', $data, ['useLayout' => false]);
            $html = ob_get_clean();
    
            echo json_encode([
                'success' => true,
                'html' => $html
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * NOVO MÉTODO: Busca apenas o HTML da conversa para ser injetado no widget.
     */
    public function fetchConversation($partner_id) {
        header('Content-Type: application/json');
        try {
            $partner_id = (int)$partner_id;
            $currentUserId = (int)$_SESSION['user_id'];

            $this->messageModel->markConversationAsRead($currentUserId, $partner_id);

            $data = [
                'messages' => $this->messageModel->getMessagesBetweenUsers($currentUserId, $partner_id),
                'current_partner_id' => $partner_id,
                'partner' => $this->userModel->getById($partner_id),
            ];

            ob_start();
            // Renderiza uma view parcial apenas com a conversa
            $this->view('messages/conversation_partial', $data, ['useLayout' => false]);
            $html = ob_get_clean();

            echo json_encode(['success' => true, 'html' => $html]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Método chamado via AJAX para enviar uma nova mensagem.
     */
    public function send() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método inválido']);
            return;
        }

        $conteudo = trim(htmlspecialchars($_POST['conteudo'] ?? '', ENT_QUOTES, 'UTF-8'));
        $id_destinatario = (int)($_POST['id_destinatario'] ?? 0);
        $id_remetente = (int)$_SESSION['user_id'];

        if (empty($conteudo) || empty($id_destinatario)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Conteúdo e destinatário são obrigatórios.']);
            return;
        }

        $lastInsertId = $this->messageModel->sendMessage([
            'id_remetente' => $id_remetente,
            'id_destinatario' => $id_destinatario,
            'conteudo' => $conteudo // O conteúdo já foi sanitizado
        ]);

        if ($lastInsertId) {
            // Busca a mensagem recém-criada para retornar com todos os dados
            $message = $this->messageModel->getMessageById($lastInsertId);
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao salvar a mensagem']);
        }
    }

    /**
     * Método chamado via AJAX (polling) para buscar novas mensagens.
     */
    public function fetchNewMessages($partner_id) {
        header('Content-Type: application/json');
        $partner_id = (int)$partner_id;
        $current_user_id = (int)$_SESSION['user_id'];

        // Busca as mensagens não lidas do parceiro para o usuário atual.
               $newMessages = $this->messageModel->getUnreadMessagesFromPartner($current_user_id, $partner_id);


        echo json_encode(['success' => true, 'messages' => $newMessages]);
    }

    /**
     * Marca as mensagens de uma conversa como lidas.
     * Chamado via AJAX quando o usuário está com a janela do chat aberta.
     */
    public function markAsRead($partner_id) {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método inválido.']);
            return;
        }

        $partner_id = (int)$partner_id;
        $current_user_id = (int)$_SESSION['user_id'];

        $this->messageModel->markConversationAsRead($current_user_id, $partner_id);

        echo json_encode(['success' => true]);
    }

    /**

     * Deleta uma mensagem específica.
     */
    public function deleteMessage($message_id) {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'error' => 'Método inválido.']);
            return;
        }

        $message = $this->messageModel->getMessageById($message_id);

        // Garante que apenas o remetente pode excluir a mensagem
        if ($message && $message->id_remetente == $_SESSION['user_id']) {
            if ($this->messageModel->deleteMessage($message_id)) {
                echo json_encode(['success' => true, 'message_id' => $message_id]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao deletar a mensagem.']);
            }
        } else {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'error' => 'Você não tem permissão para deletar esta mensagem.']);
        }
    }

    /**
     * Busca a contagem de mensagens não lidas para o usuário atual.
     * Retorna um JSON com o total e a contagem por parceiro.
     */
    public function unreadCounts() {
        header('Content-Type: application/json');
        try {
            $currentUserId = (int)$_SESSION['user_id'];
            $counts = $this->messageModel->getUnreadMessageCounts($currentUserId);
            
            echo json_encode(['success' => true, 'counts' => $counts]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erro ao buscar contagem de mensagens.']);
        }
    }

    /**
     * Busca o conteúdo HTML atualizado para o dropdown de mensagens.
     */
    public function fetchDropdownContent() {
        require_once APPROOT . '/helpers/date_helper.php';
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'html' => '']);
            return;
        }

        $currentUserId = (int)$_SESSION['user_id'];
        $unreadCount = $this->messageModel->countUnreadMessages($currentUserId);
        $recentConversations = $this->messageModel->getRecentConversations($currentUserId);

        ob_start();
        ?>
        <li class="dropdown-header">
            Você tem <?= $unreadCount ?? 0; ?> nova(s) mensagem(ns)
            <a href="<?= URLROOT; ?>/messages"><span class="badge rounded-pill bg-primary p-2 ms-2">Ver todas</span></a>
        </li>
        <li><hr class="dropdown-divider"></li>

        <?php if (!empty($recentConversations)): ?>
            <?php foreach(array_slice($recentConversations, 0, 3) as $convo): ?>
                <li class="message-item">
                    <a href="<?= URLROOT; ?>/messages/conversation/<?= $convo->partner_id; ?>">
                        <img src="<?= URLROOT . ($convo->partner_foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="" class="rounded-circle">
                        <div>
                            <h4><?= htmlspecialchars($convo->partner_name); ?></h4>
                            <p><?= htmlspecialchars(substr($convo->last_message, 0, 40)); ?>...</p>
                            <p class="timeago" datetime="<?= date('c', strtotime($convo->timestamp)); ?>"></p>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
            <?php endforeach; ?>
        <?php endif; ?>

        <li class="dropdown-footer">
            <a href="<?= URLROOT; ?>/messages">Mostrar todas as mensagens</a>
        </li>
        <?php
        $html = ob_get_clean();
        echo json_encode(['success' => true, 'html' => $html]);
    }
}