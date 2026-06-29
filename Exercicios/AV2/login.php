<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
        header('Content-Type: application/json');
        
        echo json_encode([
            "status" => "sucesso", 
            "mensagem" => "Código de verificação enviado para: " . $_POST["email"]
        ]);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso - Albergue Santa Teresa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="topo-site topo-login">
        <div class="container-topo">
            <span class="site-url" style="font-size: 1rem; opacity: 1;">Alberguesantateresa.com</span>
        </div>
    </header>

    <div class="container-voltar">
        <a href="albergue.php" class="btn-voltar-figma" title="Voltar">
            <svg viewBox="0 0 24 24" width="34" height="34" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 14L4 9l5-5"/>
                <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5v0a5.5 5.5 0 0 1-5.5 5.5H11"/>
            </svg>
        </a>
    </div>

    <main class="area-card-login">
        <div class="card-cinza-login">
            
            <h1 class="titulo-card-login">Faça o login ou Crie uma conta</h1>
            <div class="linha-separadora-titulo"></div>

            <form id="form-login" onsubmit="realizarLogin(event)">
                <div class="grupo-input-login">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <button type="submit" class="btn-acao-login">Continuar</button>
            </form>

            <div class="divisor-ou">
                <span>ou</span>
            </div>

            <div class="botoes-sociais-box">
                <button type="button" class="btn-acao-login" onclick="loginSocial('Facebook')">Continuar com Facebook</button>
                <button type="button" class="btn-acao-login" onclick="loginSocial('Google')">Continuar com Google</button>
                <button type="button" class="btn-acao-login" onclick="loginSocial('Apple')">Continuar com Apple</button>
            </div>

        </div>
    </main>

    <script>
        function realizarLogin(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;

            const dados = new FormData();
            dados.append('email', email);

            fetch('login.php', {
                method: 'POST',
                body: dados
            })
            .then(res => res.json())
            .then(resposta => {
                alert(resposta.mensagem);
                window.location.href = 'albergue.php';
            })
            .catch(erro => console.error(erro));
        }

        function loginSocial(rede) {
            alert(`Conectando com ${rede}...`);
        }
    </script>
</body>
</html>