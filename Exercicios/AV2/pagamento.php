<?php
    $id = $_GET['id'] ?? 1;

    $conn = new mysqli("localhost", "root", "", "bd_albergue");
    if ($conn->connect_error) {
        die("Falha na conexão com o banco.");
    }
    $conn->set_charset("utf8mb4");

    // Processa a reserva
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
        header('Content-Type: application/json');
        
        $quarto_id = $_POST["quarto_id"];
        $valor = $_POST["valor"];
        $nome = $_POST["nome"];       // Captura o nome
        $email = $_POST["email"];     // Captura o email
        
        // Insere os dados reais no banco
        $stmt = $conn->prepare("INSERT INTO reservas (quarto_id, cliente_nome, cliente_email, data_checkin, data_checkout, qtd_pessoas, valor_total) VALUES (?, ?, ?, '2026-07-10', '2026-07-14', 4, ?)");
        $stmt->bind_param("issd", $quarto_id, $nome, $email, $valor);
        
        echo json_encode(["status" => $stmt->execute() ? "sucesso" : "erro"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM quartos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $quarto = $stmt->get_result()->fetch_assoc();

    if (!$quarto) {
        header("Location: albergue.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - Albergue Santa Teresa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="topo-site topo-login">
        <div class="container-topo">
            <span class="site-url" style="font-size: 1rem;">Alberguesantateresa.com</span>
        </div>
    </header>

    <div class="container-voltar">
        <a href="detalhe.php?id=<?= $quarto['id'] ?>" class="btn-voltar-figma" title="Voltar">
            <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        </a>
    </div>

    <main class="conteudo-principal">
        
        <div class="barra-acento-mustard"></div>
        <h1 class="titulo-pagamento">MÉTODO DE PAGAMENTO</h1>

        <div class="box-disponibilidade-ok">
            ✅ Quarto Disponível! O quarto "<?= $quarto['titulo'] ?>" está reservado por 15 minutos.
        </div>

        <div id="bloco-formulario">
            <p class="texto-intro-pagamento">Insira seus dados para confirmar a reserva.</p>

            <form id="form-cartao" onsubmit="processarPagamento(event, <?= $quarto['id'] ?>, <?= $quarto['preco'] ?>)">
                
                <div class="campo-cartao-figma">
                    <label>• Nome Completo:</label>
                    <input type="text" id="nome-cliente" placeholder="Seu nome" required>
                </div>
                <div class="campo-cartao-figma">
                    <label>• E-mail:</label>
                    <input type="email" id="email-cliente" placeholder="seu@email.com" required>
                </div>

                <h3 class="subtitulo-figma" style="margin-top: 20px;">Informações do Cartão:</h3>
                <div class="campo-cartao-figma">
                    <label>• Número do Cartão:</label>
                    <input type="text" placeholder="0000 0000 0000 0000" required>
                </div>

                <div class="campo-cartao-figma">
                    <label>• Data de Validade:</label>
                    <input type="text" placeholder="MM/AA" required style="max-width: 180px;">
                </div>
                <div class="campo-cartao-figma">
                    <label>• Código de Segurança (CVV):</label>
                    <input type="text" placeholder="123" required style="max-width: 120px;">
                </div>

                <div class="botoes-checkout-box">
                    <button type="submit" class="btn-confirmar-figma">• [Confirmar Pagamento]</button>
                    <button type="button" class="btn-cancelar-figma" onclick="window.location.href='albergue.php'">• [Cancelar]</button>
                </div>
            </form>
        </div>

        <div id="bloco-recibo" style="display: none;">
            <h2 class="titulo-recibo">Confirmação de Pagamento:</h2>
            <p class="sub-sucesso">Pagamento Concluído com Sucesso!</p>
            <button class="btn-checar" style="max-width: 300px; margin-top: 30px;" onclick="window.location.href='albergue.php'">Voltar para a Página Inicial</button>
        </div>

    </main>

    <script>
        function processarPagamento(event, idQuarto, precoDiaria) {
            event.preventDefault();

            const dados = new FormData();
            dados.append('acao', 'finalizar');
            dados.append('quarto_id', idQuarto);
            dados.append('valor', precoDiaria * 4);
            dados.append('nome', document.getElementById('nome-cliente').value);
            dados.append('email', document.getElementById('email-cliente').value);

            fetch(window.location.href, {
                method: 'POST',
                body: dados
            })
            .then(res => res.json())
            .then(resposta => {
                if(resposta.status === 'sucesso') {
                    document.getElementById('bloco-formulario').style.display = 'none';
                    document.getElementById('bloco-recibo').style.display = 'block';
                }
            })
            .catch(err => console.error(err));
        }
    </script>
</body>
</html>