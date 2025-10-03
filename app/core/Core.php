<?php
// Roteador principal que lê a URL e chama o controller apropriado
class Core {
    protected $currentController = 'AuthController';
    protected $currentMethod = 'login';
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();
        if(isset($url[0]) && file_exists(APPROOT . '/controllers/' . ucwords($url[0]) . 'Controller.php')){
            $this->currentController = ucwords($url[0]) . 'Controller';
            unset($url[0]);
            $this->currentMethod = 'index'; // Reseta o método padrão para 'index'
        }
        require_once APPROOT . '/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController;
        if(isset($url[1]) && method_exists($this->currentController, $url[1])){
            $this->currentMethod = $url[1];
            unset($url[1]);
        }
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
    public function getUrl(){ if(isset($_GET['route'])){ return explode('/', filter_var(rtrim($_GET['route'], '/'), FILTER_SANITIZE_URL)); } return []; }
}