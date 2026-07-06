<?php
session_start();

// Garante que a resposta será sempre em formato JSON
header('Content-Type: application/json');

// Verifica se a requisição é um POST válido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
    
    // Conexão com o banco
    $conn = new mysqli("localhost", "root", "", "bd_albergue");
    if ($conn->connect_error) {
        echo json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]);
        exit;
    }
    
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $acao = $_POST["acao"];

    // --- LÓGICA DE LOGIN ---
    if ($acao == 'login') {
        $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(["status" => "erro", "mensagem" => "E-mail não encontrado. Por favor, clique em Cadastre-se!"]);
            exit;
        }

        $user = $result->fetch_assoc();
        
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_logado'] = $email;
            echo json_encode(["status" => "sucesso", "mensagem" => "Login efetuado com sucesso! Entrando..."]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Senha incorreta! Tente novamente."]);
        }
        exit;
    }

    // --- LÓGICA DE CADASTRO ---
    if ($acao == 'cadastrar') {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            echo json_encode(["status" => "erro", "mensagem" => "Este e-mail já está cadastrado! Faça login."]);
            exit;
        }

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $senha_hash);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "sucesso", "mensagem" => "Cadastro realizado com sucesso! Agora você pode fazer o login."]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Erro ao cadastrar usuário."]);
        }
        exit;
    }
} else {
    // Se tentarem acessar a página diretamente pelo navegador sem enviar dados
    echo json_encode(["status" => "erro", "mensagem" => "Acesso não autorizado."]);
}
?>