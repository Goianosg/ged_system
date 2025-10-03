<?php

class UserProfileDTO {
    public $id;
    public $email;
    public $senha_atual;
    public $newPassword;
    public $confirmPassword;
    public $foto_path;

    public function __construct(
        $id,
        $email,
        $senha_atual,
        $newPassword,
        $confirmPassword,
        $foto_path
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->senha_atual = $senha_atual;
        $this->newPassword = $newPassword;
        $this->confirmPassword = $confirmPassword;
        $this->foto_path = $foto_path;
    }
}



?>