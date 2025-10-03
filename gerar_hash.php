<?php

// A senha que queremos usar
$senha = '123456';

// Gerando um novo hash seguro
$novo_hash = password_hash($senha, PASSWORD_DEFAULT);

// Exibindo o hash na tela para podermos copiar
echo "<h1>Seu Novo Hash Confiável</h1>";
echo "<p>Copie a linha de texto abaixo e use-a no Passo 2.</p>";
echo '<input type="text" value="' . htmlspecialchars($novo_hash) . '" size="70" readonly>';

?>