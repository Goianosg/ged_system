<?php
class MessagesController extends Controller {
    private $messageModel;
    private $userModel;    

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit();
        }
        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('User');        
    }

    public function index() {
        // Apenas redireciona para a página de conversa sem ninguém selecionado
        $this->conversation(null);
    }

    public function conversation($partnerId = null) {
        if (!in_array('use_chat', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }

        $currentUserId = $_SESSION['user_id'];
        $messages = [];
        if ($partnerId) {
            $messages = $this->messageModel->getMessagesBetweenUsers($currentUserId, $partnerId);
            $this->messageModel->markConversationAsRead($currentUserId, $partnerId);
        }

        $data = [
            'users' => $this->userModel->getAllSortedByConversation($currentUserId),
            'messages' => $messages,
            'current_partner_id' => $partnerId,
            'partner' => $partnerId ? $this->userModel->getById($partnerId) : null
        ];
        $this->view('messages/page', $data);
    }
}
