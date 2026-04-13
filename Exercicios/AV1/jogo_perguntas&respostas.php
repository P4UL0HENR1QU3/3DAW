<?php
    $mensagem = "";

    if (isset($_POST["acao"])) {

        if ($_POST["acao"] == "Cadastrar Usuario") {
            $nome = $_POST["nome_usuario"];
            $email = $_POST["email_usuario"];

            $arq = fopen("usuarios.txt", "a");
            $linha = "\n" . $nome . ";" . $email;
            fwrite($arq, $linha);
            fclose($arq);
            $mensagem = "Usuário salvo com sucesso!";
        }

        if ($_POST["acao"] == "Incluir Multipla") {
            $id = $_POST["id"];
            $pergunta = $_POST["pergunta"];
            $opcoes = $_POST["opcoes"];
            $resposta = $_POST["resposta"];

            $arq = fopen("perguntas.txt", "a");
            $linha = "\n" . $id . ";Multipla;" . $pergunta . ";" . $opcoes . ";" . $resposta;
            fwrite($arq, $linha);
            fclose($arq);
            $mensagem = "Pergunta de múltipla escolha salva!";
        }

        if ($_POST["acao"] == "Incluir Texto") {
            $id = $_POST["id"];
            $pergunta = $_POST["pergunta"];
            $resposta = $_POST["resposta"];

            $arq = fopen("perguntas.txt", "a");
            $linha = "\n" . $id . ";Texto;" . $pergunta . ";-;" . $resposta;
            fwrite($arq, $linha);
            fclose($arq);
            $mensagem = "Pergunta de texto salva!";
        }
    }
?>
<html>
<head>
    <title>Jogo - Sr. Water Falls</title>
</head>
<body>

    <p><b><?php echo $mensagem; ?></b></p>

    <h1>Cadastro de Usuários</h1>
    <form method="POST" action="">
        Nome do Gestor: <input type="text" name="nome_usuario"><br><br>
        E-mail: <input type="text" name="email_usuario"><br><br>
        <input type="submit" name="acao" value="Cadastrar Usuario">
    </form>

    <hr>

    <h1>1. Incluir Pergunta de Multipla Escolha</h1>
    <form method="POST" action="">
        ID: <input type="text" name="id"><br><br>
        Pergunta: <input type="text" name="pergunta"><br><br>
        Opções: <input type="text" name="opcoes"><br><br>
        Resposta: <input type="text" name="resposta"><br><br>
        <input type="submit" name="acao" value="Incluir Multipla">
    </form>

    <hr>

    <h1>2. Incluir Pergunta de Texto</h1>
    <form method="POST" action="">
        ID: <input type="text" name="id"><br><br>
        Pergunta: <input type="text" name="pergunta"><br><br>
        Resposta: <input type="text" name="resposta"><br><br>
        <input type="submit" name="acao" value="Incluir Texto">
    </form>

    <hr>
    
    <h2>Próximos passos a desenvolver...</h2>
    <ul>
        <li>3. Alterar Perguntas e suas respostas de multipla escolha</li>
        <li>4. Alterar Perguntas com respostas de texto</li>
        <li>5. Listar Perguntas e respostas</li>
        <li>6. Listar uma Pergunta</li>
        <li>7. Excluir Pergunta e respostas</li>
    </ul>

</body>
</html>