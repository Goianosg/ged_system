<?php
class ReportsController extends Controller {
    private $activityLogModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . URLROOT . '/auth/login'); exit(); }
        // Proteja com uma nova permissão
        if (!in_array('view_reports', $_SESSION['user_permissions'])) {
            header('Location: ' . URLROOT . '/dashboard'); exit();
        }
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // Renomeamos para activityLog para ser específico
    public function activityLog() {
        $data = ['logs' => $this->activityLogModel->getAll()];
        $this->view('reports/activity_log', $data);
    }
}