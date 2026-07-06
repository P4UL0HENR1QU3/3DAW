<?php
session_start();
header('Content-Type: application/json');

// Se existir uma sessão ativa, devolve verde (true) e o email
if (isset($_SESSION['usuario_logado'])) {
    echo json_encode([
        "logado" => true, 
        "email" => $_SESSION['usuario_logado']
    ]);
} else {
    // Se não, devolve vermelho (false)
    echo json_encode([
        "logado" => false
    ]);
}
?>