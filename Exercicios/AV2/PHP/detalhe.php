<?php
// Garante que a resposta será em formato JSON
header('Content-Type: application/json');

// Recebe o ID (se não vier nada, assume o ID 1 por segurança)
$id = $_GET['id'] ?? 1;

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bd_albergue");
if ($conn->connect_error) {
    echo json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]);
    exit;
}
$conn->set_charset("utf8mb4");

// Busca o quarto selecionado
$stmt = $conn->prepare("SELECT * FROM quartos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$quarto = $stmt->get_result()->fetch_assoc();

// Verifica se encontrou o quarto
if ($quarto) {
    echo json_encode(["status" => "sucesso", "dados" => $quarto]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Quarto não encontrado."]);
}
?>