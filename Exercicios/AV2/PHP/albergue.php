<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bd_albergue");

if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]));
}
$conn->set_charset("utf8mb4");

// Ouve o pedido GET do JavaScript e devolve a lista de quartos
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["acao"])) {
    header('Content-Type: application/json');

    if ($_GET["acao"] == "listar_quartos") {
        $categoria = $_GET["categoria"] ?? "";
        
        if ($categoria != "") {
            $stmt = $conn->prepare("SELECT id, categoria, titulo, preco, capacidade, imagem FROM quartos WHERE categoria = ?");
            $stmt->bind_param("s", $categoria);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT id, categoria, titulo, preco, capacidade, imagem FROM quartos");
        }
        
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        exit;
    }
}
?>