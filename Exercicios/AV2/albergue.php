<?php
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "bd_albergue");
    
    if ($conn->connect_error) {
        die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]));
    }
    $conn->set_charset("utf8mb4");

    // Requisições AJAX via GET (Busca de dados)
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
            
            // Retorna os dados direto em JSON para o frontend
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            exit;
        }
    }

    // Requisições AJAX via POST (Salvando no banco)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
        header('Content-Type: application/json');

        if ($_POST["acao"] == "fazer_reserva") {
            $quarto_id = $_POST["quarto_id"] ?? "";
            $nome = $_POST["cliente_nome"] ?? "";
            $email = $_POST["cliente_email"] ?? "";
            $checkin = $_POST["data_checkin"] ?? "";
            $checkout = $_POST["data_checkout"] ?? "";
            $pessoas = $_POST["qtd_pessoas"] ?? "";
            $total = $_POST["valor_total"] ?? 0.00;

            $stmt = $conn->prepare("INSERT INTO reservas (quarto_id, cliente_nome, cliente_email, data_checkin, data_checkout, qtd_pessoas, valor_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssii", $quarto_id, $nome, $email, $checkin, $checkout, $pessoas, $total);

            if ($stmt->execute()) {
                echo json_encode(["status" => "sucesso", "mensagem" => "Reserva efetuada com sucesso!"]);
            } else {
                echo json_encode(["status" => "erro", "mensagem" => "Erro ao processar a reserva."]);
            }
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albergue Santa Teresa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="topo-site">
        <div class="container-topo">
            
            <div class="logo-box">
    <span class="site-url">Alberguesantateresa.com</span>
    <img src="imagens/bondinho.jpg" alt="Logo Bondinho" class="logo-site">
            </div>

            <div class="menu-superior">
                <div class="seletor-idioma">
                    <span>🇧🇷 PT 🞃</span>
                </div>
                <a href="#" class="btn-secundario">Cadastre-se</a>
                <a href="login.php" class="btn-primario">Login</a>
            </div>

        </div>

        <div class="barra-reserva">
            <div class="campo-input">
                <label>📅 Check-in</label>
                <input type="date" id="search-checkin">
            </div>
            <div class="campo-input">
                <label>📅 Check-out</label>
                <input type="date" id="search-checkout">
            </div>
            <div class="campo-input">
                <label>👤 Pessoas</label>
                <select id="search-pessoas">
                    <option value="1">1 Pessoa</option>
                    <option value="2">2 Pessoas</option>
                    <option value="4" selected>4 Pessoas</option>
                    <option value="6">6+ Pessoas</option>
                </select>
            </div>
            <button class="btn-buscar" onclick="filtrarQuartos()">Buscar</button>
        </div>

        <nav class="submenu-abas">
            <a href="#" class="aba-ativa">Visão Geral</a>
            <a href="#">Quartos</a>
            <a href="#">Comodidades</a>
            <a href="#">Regras de convivência</a>
        </nav>
    </header>

    <main class="conteudo-principal">

        <section class="destaque-albergue">
            <div class="destaque-texto">
                <h1>Albergue Santa Teresa</h1>
                <div class="box-endereco">
                    <p>📍 Cidade: Rio de Janeiro, Bairro: Santa Teresa, Rua XXXX, Cep: xxxxx-xxx, Brasil</p>
                </div>
            </div>
            <div class="destaque-banner">
                <div class="banner-chamada">
                    <h2>VENHA CONHECER SANTA TERESA!</h2>
                </div>
            </div>
        </section>

        <section class="sessao-quartos">
            <h2 class="titulo-sessao">OFERTAS ESPECIAIS!</h2>
            <div class="grid-4-colunas" id="grid-especiais"></div>
        </section>

        <section class="sessao-quartos">
            <h2 class="titulo-sessao">OFERTAS PADRÃO!</h2>
            <div class="grid-4-colunas" id="grid-padrao"></div>
        </section>

    </main>

    <footer class="rodape-site">
        <div class="redes-sociais">
            <p>Siga-nos nas redes sociais</p>
            <div class="icones-redes">
                <span>[f]</span> <span>[ig]</span> <span>[P]</span> <span>[yt]</span> <span>[tk]</span> <span>[X]</span>
            </div>
        </div>

        <div class="rodape-links">
            <p>XXX/2026 Alberguesantateresa.com</p>
            <div class="sub-links">
                <a href="#">Aviso sobre a proteção de dados</a>
                <a href="#">Termos e condições gerais</a>
                <a href="#">Administração</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            carregarQuartos('especial', 'grid-especiais');
            carregarQuartos('padrao', 'grid-padrao');
        });

        function carregarQuartos(categoria, elementoId) {
            fetch(`?acao=listar_quartos&categoria=${categoria}`)
                .then(response => response.json())
                .then(quartos => {
                    const grid = document.getElementById(elementoId);
                    grid.innerHTML = "";

                    if (quartos.length === 0) return;

                    quartos.forEach(quarto => {
                        grid.innerHTML += `
                            <div class="card-quarto" onclick="window.location.href='detalhe.php?id=${quarto.id}'">
                                <div class="card-imagem">
                                    <img src="${quarto.imagem}" alt="${quarto.titulo}">
                                </div>
                                <div class="card-rodape-preco">
                                    <span class="nome-quarto">${quarto.titulo}</span>
                                    <span class="preco-quarto">R$ ${parseFloat(quarto.preco).toFixed(2)}</span>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(erro => console.error("Erro ao buscar quartos:", erro));
        }
    </script>
</body>
</html>