<?php
    $id = $_GET['id'] ?? 1;

    $conn = new mysqli("localhost", "root", "", "bd_albergue");
    if ($conn->connect_error) {
        die("Falha na conexão com o banco.");
    }
    $conn->set_charset("utf8mb4");

    // Busca o quarto selecionado
    $stmt = $conn->prepare("SELECT * FROM quartos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $quarto = $stmt->get_result()->fetch_assoc();

    // Se digitarem um ID inexistente na URL, volta para a home
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
    <title><?= $quarto['titulo'] ?> - Albergue</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="topo-site topo-login">
        <div class="container-topo">
            <span class="site-url" style="font-size: 1rem;">Alberguesantateresa.com</span>
        </div>
    </header>

    <div class="container-voltar">
        <a href="albergue.php" class="btn-voltar-figma" title="Voltar para a Home">
            <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        </a>
    </div>

    <main class="conteudo-principal">
        <div class="grid-detalhe-figma">
            
            <div class="coluna-esq-detalhe">
                <h1 class="titulo-detalhe">OFERTA <?= strtoupper($quarto['categoria']) ?>! - R$ <?= number_format($quarto['preco'], 2, ',', '.') ?></h1>
                
                <div class="foto-destaque-box">
                    <img src="<?= $quarto['imagem'] ?>" alt="<?= $quarto['titulo'] ?>">
                </div>

                <div class="tags-flex-box">
                    <span class="tag-figma"><?= $quarto['capacidade'] ?> Camas</span>
                    <span class="tag-figma">Tem Banheiro</span>
                    <span class="tag-figma">Tem Janela</span>
                    <span class="tag-figma">Wi-Fi Grátis</span>
                </div>

                <div class="area-comentario-figma">
                    <div class="topo-comentario">
                        <span>Adicione um comentário</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <textarea placeholder="Escreva uma pergunta para a recepção..."></textarea>
                </div>
            </div>

            <div class="coluna-dir-detalhe">
                <div class="box-reserva-figma">
                    <div class="topo-box-reserva">Reserve já!</div>
                    <div class="miolo-box-reserva">
                        <p style="margin-bottom:15px; font-size:0.85rem; color:#555;">Diária calculada para <?= $quarto['capacidade'] ?> hóspedes.</p>
                        <button class="btn-checar" onclick="window.location.href='pagamento.php?id=<?= $quarto['id'] ?>'">Checar Disponibilidade</button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="rodape-site">
        <div class="rodape-links"><p>XXX/2026 Alberguesantateresa.com</p></div>
    </footer>

</body>
</html>