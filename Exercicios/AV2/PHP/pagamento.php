<?php
// Garante que a resposta será sempre em JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"]) && $_POST["acao"] == "finalizar") {
    
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "bd_albergue");
    if ($conn->connect_error) {
        echo json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]);
        exit;
    }
    $conn->set_charset("utf8mb4");

    // Recebe os dados do formulário HTML
    $quarto_id = $_POST["quarto_id"];
    $valor = $_POST["valor"];
    $nome = $_POST["nome"];       
    $email = $_POST["email"];     
    
    // Insere os dados reais no banco (mantivemos as datas fixas para o protótipo da AV2)
    $stmt = $conn->prepare("INSERT INTO reservas (quarto_id, cliente_nome, cliente_email, data_checkin, data_checkout, qtd_pessoas, valor_total) VALUES (?, ?, ?, '2026-07-10', '2026-07-14', 4, ?)");
    $stmt->bind_param("issd", $quarto_id, $nome, $email, $valor);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "sucesso", "mensagem" => "Reserva efetuada com sucesso!"]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao gravar reserva."]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Acesso inválido."]);
}
?>
