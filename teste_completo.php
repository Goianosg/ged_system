<?php

echo "<h1>Teste Completo de Hash e Verificação</h1>";

// 1. Definimos uma senha
$senha = '123456';
echo "<p><b>Senha Original:</b> " . $senha . "</p>";

// 2. O PHP gera um hash para essa senha
$hash_gerado = password_hash($senha, PASSWORD_DEFAULT);   
  
echo "<p><b>Hash Gerado Agora:</b> " . $hash_gerado . "</p>";
echo "<hr>";

// 3. Verificamos IMEDIATAMENTE se a senha original corresponde ao hash que acabamos de gerar.
// Este teste DEVE retornar SUCESSO em uma instalação normal do PHP.
if (password_verify($senha, $hash_gerado)) {
    echo "<h2 style='color:green;'>RESULTADO: SUCESSO! O PHP está funcionando corretamente.</h2>";
} else {
    echo "<h2 style='color:red;'>RESULTADO: FALHA CRÍTICA! Sua instalação do PHP está com problemas.</h2>";
}

echo "<hr>";
echo "<h3>Versão do PHP: " . phpversion() . "</h3>";

?>