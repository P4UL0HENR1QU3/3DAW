<?php
session_start();
header('Content-Type: application/json');

// Proteção: Se a pessoa não estiver logada, expulsa-a
if (!isset($_SESSION['usuario_logado'])) {
    echo json_encode(["status" => "erro", "mensagem" => "Não autorizado."]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "bd_albergue");
$conn->set_charset("utf8mb4");

$email_logado = $_SESSION['usuario_logado'];

// Faz um JOIN para buscar os detalhes da reserva E o nome do quarto
$stmt = $conn->prepare("SELECT r.*, q.titulo AS nome_quarto 
                        FROM reservas r 
                        JOIN quartos q ON r.quarto_id = q.id 
                        WHERE r.cliente_email = ? 
                        ORDER BY r.data_checkin DESC");
$stmt->bind_param("s", $email_logado);
$stmt->execute();
$reservas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(["status" => "sucesso", "dados" => $reservas]);
?>