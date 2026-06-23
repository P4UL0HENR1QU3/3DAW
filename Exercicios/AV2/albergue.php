<?php
    $conn = new mysqli("localhost", "root", "", "bd_albergue");
    
    if ($conn->connect_error) {
        die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão com a base de dados."]));
    }
    $conn->set_charset("utf8mb4");

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
            
            $quartos = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($quartos);
            exit;
        }
    }

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
                echo json_encode(["status" => "erro", "mensagem" => "Erro ao processar a transação."]);
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
                <div class="placeholder-logo">[ Logo Bondinho ]</div>
            </div>

            <div class="menu-superior">
                <div class="seletor-idioma">
                    <span>🇧🇷 PT 🞃</span>
                </div>
                <a href="#" class="btn-secundario">Cadastre-se</a>
                <a href="#" class="btn-primario">Login</a>
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

                    if (quartos.length === 0) {
                        grid.innerHTML = "<p>Nenhum quarto disponível para esta categoria.</p>";
                        return;
                    }

                    quartos.forEach(quarto => {
                        grid.innerHTML += `
                            <div class="card-quarto" onclick="abrirModalReserva(${quarto.id}, '${quarto.titulo}', ${quarto.preco})">
                                <div class="card-imagem">
                                    <img src="imagens/${quarto.imagem}" alt="${quarto.titulo}" onerror="this.src='https://via.placeholder.com/300x200/ccc/666?text=${encodeURIComponent(quarto.titulo)}'">
                                </div>
                                <div class="card-rodape-preco">
                                    <span class="nome-quarto">${quarto.titulo}</span>
                                    <span class="preco-quarto">R$ ${parseFloat(quarto.preco).toFixed(2)}</span>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(erro => console.error(erro));
        }

        function abrirModalReserva(id, titulo, preco) {
            const nomeCliente = prompt(`Deseja simular uma reserva para:\n${titulo} (R$ ${preco}/noite)?\n\nIntroduza o seu nome:`);
            if (!nomeCliente) return;

            const emailCliente = prompt("Introduza o seu e-mail:");
            if (!emailCliente) return;

            const dadosForm = new FormData();
            dadosForm.append('acao', 'fazer_reserva');
            dadosForm.append('quarto_id', id);
            dadosForm.append('cliente_nome', nomeCliente);
            dadosForm.append('cliente_email', emailCliente);
            dadosForm.append('data_checkin', document.getElementById('search-checkin').value || '2026-07-01');
            dadosForm.append('data_checkout', document.getElementById('search-checkout').value || '2026-07-05');
            dadosForm.append('qtd_pessoas', document.getElementById('search-pessoas').value);
            dadosForm.append('valor_total', preco * 4);

            fetch(window.location.href, {
                method: 'POST',
                body: dadosForm
            })
            .then(response => response.json())
            .then(resposta => {
                alert(resposta.mensagem);
            })
            .catch(erro => console.error(erro));
        }

        function filtrarQuartos() {
            alert("Filtrando quartos disponíveis...");
        }
    </script>
</body>
</html>