<?php

// Este é o hash que está no seu banco de dados para o usuário 'goianosgo'
$hash_do_banco = '$2y$10$65BfsIeUk/emoRgQNNJ2f.zodaFSqGwvhd7LRt/7uIr34qkJ3KYOS';

// Esta é a senha que você está digitando na tela de login
$senha_digitada = '123456';

echo "<h1>Teste da Função password_verify()</h1>";
echo "<p><b>Hash do Banco:</b> " . htmlspecialchars($hash_do_banco) . "</p>";
echo "<p><b>Senha Digitada:</b> " . htmlspecialchars($senha_digitada) . "</p>";
echo "<hr>";

// A função principal que está falhando no login
if (password_verify($senha_digitada, $hash_do_banco)) {
    echo "<h2 style='color:green;'>RESULTADO: SUCESSO! A senha corresponde ao hash.</h2>";
    echo "<p>Se você está vendo esta mensagem, o problema não é no PHP, mas sim em como os dados estão sendo passados do seu banco para o controller.</p>";
} else {
    echo "<h2 style='color:red;'>RESULTADO: FALHA! A senha NÃO corresponde ao hash.</h2>";
    echo "<p>Se você está vendo esta mensagem, há um problema com a sua versão do PHP ou com a configuração do XAMPP, pois esta verificação deveria funcionar.</p>";
}

echo "<hr>";
echo "<h3>Versão do PHP: " . phpversion() . "</h3>";

?>