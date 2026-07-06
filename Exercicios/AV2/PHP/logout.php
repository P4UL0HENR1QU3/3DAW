<?php
session_start();

// Destrói todas as informações da sessão atual
session_unset();
session_destroy();

header('Content-Type: application/json');
echo json_encode(["status" => "sucesso", "mensagem" => "Sessão encerrada."]);
?>