<?php
class Controller {
    public function model($model){
        require_once APPROOT . '/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [], $options = []) {
        $useLayout = $options['useLayout'] ?? true;
        $returnAsString = $options['returnAsString'] ?? false;

        if ($useLayout) require_once APPROOT . '/helpers/date_helper.php';
        // Carrega dados comuns para o layout (chat e usuário)
        if ($useLayout && isset($_SESSION['user_id'])) {
            if (!isset($data['unread_messages_count']) || !isset($data['recent_conversations'])) {
                $messageModel = $this->model('Message');
                $data['unread_messages_count'] = $messageModel->countUnreadMessages($_SESSION['user_id']);
                $data['recent_conversations'] = $messageModel->getRecentConversations($_SESSION['user_id']);
            }
        }

        $viewPath = APPROOT . '/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            if ($returnAsString) {
                ob_start();
                require $viewPath;
                return ob_get_clean();
            }

            if ($useLayout) {
                require_once APPROOT . '/views/inc/header.php';
                require_once APPROOT . '/views/inc/sidebar.php';
            }
            require_once APPROOT . '/views/' . $view . '.php';
            if ($useLayout) { require_once APPROOT . '/views/inc/footer.php'; }
        } else { die('View não existe.'); }
    }
}
