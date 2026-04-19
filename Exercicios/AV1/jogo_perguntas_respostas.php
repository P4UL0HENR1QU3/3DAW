<!--- FAETERJ-Rio
Desenvolvimento de Aplicações Web - 3DAW
AV1 3DAW 2026-1
Aluno:
O Sr. Water Falls precisa de um sistema de jogo corporativo, para treinar seus gestores em situações difíceis. O jogo deverá gerenciar situações de perguntas e respostas (decisões) encadeadas.
O game é composto por vários desafios e cada desafio tem um objetivo específico, como por exemplo, gerenciar o andamento de um projeto, resolver um problema administrativo, contratar um novo funcionário, conceder um empréstimo e outros.
Neste primeiro momento será desenvolvido somente o cadastro Usuários, Perguntas e Respostas.
Criar as funcionalidades de Criar Perguntas e respostas de multipla escolha, Criar Perguntas e respostas de texto,  alterar Perguntas e suas respostas de multipla escolha, listar todas Perguntas, listar uma Pergunta e excluir Pergunta e respostas.
Inicialmente usaremos arquivos texto(txt) para salvar os usuários.
As funcionalidades de Perguntas e respostas devem estar disponíveis por tela.
O código deverá ser em PHP.
Então deverá ser criado:
1. Criar Perguntas e respostas de multipla escolha.
2.Criar Perguntas e respostas de texto.
3. Alterar Perguntas e suas respostas de multipla escolha
4. Alterar Perguntas com respostas de texto
5. Listar Perguntas e repostas.
6. Listar uma Pergunta.
7. Excluir Pergunta e respostas -->


<?php
    $mensagem = "";

    if (isset($_POST["acao"])) {

        if ($_POST["acao"] == "Cadastrar Usuario") {
            $nome = $_POST["nome_usuario"] ?? "";
            $email = $_POST["email_usuario"] ?? "";

            $arq = fopen("usuarios.txt", "a");
            $linha = "\n" . $nome . ";" . $email;
            fwrite($arq, $linha);
            fclose($arq);
            $mensagem = "Usuário salvo com sucesso!";
        }

        if ($_POST["acao"] == "Incluir Multipla") {
            $id = $_POST["id"] ?? "";
            $pergunta = $_POST["pergunta"] ?? "";
            
            $opA = $_POST["opcao_a"] ?? "";
            $opB = $_POST["opcao_b"] ?? "";
            $opC = $_POST["opcao_c"] ?? "";
            $opD = $_POST["opcao_d"] ?? "";
            $opE = $_POST["opcao_e"] ?? "";

            $op = "";
            if($opA != "") $op .= "A) " . $opA . " ";
            if($opB != "") $op .= "B) " . $opB . " ";
            if($opC != "") $op .= "C) " . $opC . " ";
            if($opD != "") $op .= "D) " . $opD . " ";
            if($opE != "") $op .= "E) " . $opE;
            $opcoes = trim($op);

            $resposta = $_POST["resposta"] ?? "";

            $arq = fopen("perguntas.txt", "a");
            $linha = "\n" . $id . ";Multipla;" . $pergunta . ";" . $opcoes . ";" . $resposta;
            fwrite($arq, $linha);
            fclose($arq);
            $mensagem = "Pergunta de múltipla escolha salva!";
        }

        if ($_POST["acao"] == "Incluir Texto") {
            $id = $_POST["id"] ?? "";
            $pergunta = $_POST["pergunta"] ?? "";
            $resposta = $_POST["resposta"] ?? "";

            $arq = fopen("perguntas.txt", "a");
            $linha = "\n" . $id . ";Texto;" . $pergunta . ";-;" . $resposta;
            fwrite($arq, $linha);
            fclose($arq);
            $mensagem = "Pergunta de texto salva!";
        }

        if ($_POST["acao"] == "Alterar Multipla") {
            $id = $_POST["id"] ?? "";
            $pergunta = $_POST["pergunta"] ?? "";
            
            $opA = $_POST["opcao_a"] ?? "";
            $opB = $_POST["opcao_b"] ?? "";
            $opC = $_POST["opcao_c"] ?? "";
            $opD = $_POST["opcao_d"] ?? "";
            $opE = $_POST["opcao_e"] ?? "";

            $op = "";
            if($opA != "") $op .= "A) " . $opA . " ";
            if($opB != "") $op .= "B) " . $opB . " ";
            if($opC != "") $op .= "C) " . $opC . " ";
            if($opD != "") $op .= "D) " . $opD . " ";
            if($opE != "") $op .= "E) " . $opE;
            $opcoes = trim($op);

            $resposta = $_POST["resposta"] ?? "";

            $linhas = file("perguntas.txt");
            $arq = fopen("perguntas.txt", "w");

            foreach ($linhas as $linha) {
                $dados = explode(";", trim($linha));
                if (isset($dados[0]) && $dados[0] == $id) {
                    fwrite($arq, $id . ";Multipla;" . $pergunta . ";" . $opcoes . ";" . $resposta . "\n");
                } else {
                    if (trim($linha) != "") {
                        fwrite($arq, trim($linha) . "\n");
                    }
                }
            }
            fclose($arq);
            $mensagem = "Pergunta de múltipla escolha alterada com sucesso!";
        }

        if ($_POST["acao"] == "Alterar Texto") {
            $id = $_POST["id"] ?? "";
            $pergunta = $_POST["pergunta"] ?? "";
            $resposta = $_POST["resposta"] ?? "";

            $linhas = file("perguntas.txt");
            $arq = fopen("perguntas.txt", "w");

            foreach ($linhas as $linha) {
                $dados = explode(";", trim($linha));
                if (isset($dados[0]) && $dados[0] == $id) {
                    fwrite($arq, $id . ";Texto;" . $pergunta . ";-;" . $resposta . "\n");
                } else {
                    if (trim($linha) != "") {
                        fwrite($arq, trim($linha) . "\n");
                    }
                }
            }
            fclose($arq);
            $mensagem = "Pergunta de texto alterada com sucesso!";
        }

        if ($_POST["acao"] == "Excluir") {
            $id = $_POST["id"] ?? "";

            $linhas = file("perguntas.txt");
            $arq = fopen("perguntas.txt", "w");

            foreach ($linhas as $linha) {
                $dados = explode(";", trim($linha));
                if (isset($dados[0]) && $dados[0] != $id && trim($linha) != "") {
                    fwrite($arq, trim($linha) . "\n");
                }
            }
            fclose($arq);
            $mensagem = "Pergunta excluída com sucesso!";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Jogo de Perguntas de Respostas</title>
    <link rel="stylesheet" href="style.css">>
</head>
<body>

    <div class="container">

        <?php if ($mensagem != ""): ?>
            <div class="alerta"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <h1>Cadastro de Usuários</h1>
        <form method="POST" action="">
            <label>Nome do Gestor:</label>
            <input type="text" name="nome_usuario" required>
            
            <label>E-mail:</label>
            <input type="text" name="email_usuario" required>
            
            <input type="submit" name="acao" value="Cadastrar Usuario">
        </form>

        <h1>1. Incluir Pergunta de Múltipla Escolha</h1>
        <form method="POST" action="">
            <label>ID:</label>
            <input type="text" name="id" required>
            
            <label>Pergunta:</label>
            <input type="text" name="pergunta" required>
            
            <label>Opções (Preencha pelo menos A e B):</label>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="opcao_a" placeholder="Opção A" required style="width: 20%;">
                <input type="text" name="opcao_b" placeholder="Opção B" required style="width: 20%;">
                <input type="text" name="opcao_c" placeholder="Opção C" style="width: 20%;">
                <input type="text" name="opcao_d" placeholder="Opção D" style="width: 20%;">
                <input type="text" name="opcao_e" placeholder="Opção E" style="width: 20%;">
            </div>
            
            <label>Qual é a resposta certa?</label>
            <select name="resposta" required style="padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1em; background-color: white; outline: none; cursor: pointer;">
                <option value="">Selecione a opção...</option>
                <option value="A">Opção A</option>
                <option value="B">Opção B</option>
                <option value="C">Opção C</option>
                <option value="D">Opção D</option>
                <option value="E">Opção E</option>
            </select>
            
            <input type="submit" name="acao" value="Incluir Multipla">
        </form>

        <h1>2. Incluir Pergunta de Texto</h1>
        <form method="POST" action="">
            <label>ID:</label>
            <input type="text" name="id" required>
            
            <label>Pergunta:</label>
            <input type="text" name="pergunta" required>
            
            <label>Resposta:</label>
            <input type="text" name="resposta" required>
            
            <input type="submit" name="acao" value="Incluir Texto">
        </form>

        <h1>3. Alterar Múltipla Escolha</h1>
        <form method="POST" action="">
            <label>ID p/ Alterar:</label>
            <input type="text" name="id" required>
            
            <label>Nova Pergunta:</label>
            <input type="text" name="pergunta" required>
            
            <label>Novas Opções:</label>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="opcao_a" placeholder="Opção A" required style="width: 20%;">
                <input type="text" name="opcao_b" placeholder="Opção B" required style="width: 20%;">
                <input type="text" name="opcao_c" placeholder="Opção C" style="width: 20%;">
                <input type="text" name="opcao_d" placeholder="Opção D" style="width: 20%;">
                <input type="text" name="opcao_e" placeholder="Opção E" style="width: 20%;">
            </div>
            
            <label>Nova Resposta:</label>
            <select name="resposta" required style="padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1em; background-color: white; outline: none; cursor: pointer;">
                <option value="">Selecione a nova opção...</option>
                <option value="A">Opção A</option>
                <option value="B">Opção B</option>
                <option value="C">Opção C</option>
                <option value="D">Opção D</option>
                <option value="E">Opção E</option>
            </select>
            
            <input type="submit" name="acao" value="Alterar Multipla">
        </form>

        <h1>4. Alterar Texto</h1>
        <form method="POST" action="">
            <label>ID p/ Alterar:</label>
            <input type="text" name="id" required>
            
            <label>Nova Pergunta:</label>
            <input type="text" name="pergunta" required>
            
            <label>Nova Resposta:</label>
            <input type="text" name="resposta" required>
            
            <input type="submit" name="acao" value="Alterar Texto">
        </form>

        <h1>7. Excluir Pergunta e Respostas</h1>
        <form method="POST" action="">
            <label>ID p/ Excluir:</label>
            <input type="text" name="id" required>
            
            <input type="submit" name="acao" value="Excluir">
        </form>

        <h1>6. Listar uma Pergunta</h1>
        <form method="GET" action="" style="flex-direction: row; align-items: flex-end;">
            <div style="flex-grow: 1; display: flex; flex-direction: column; gap: 12px;">
                <label>Buscar por ID:</label>
                <input type="text" name="busca_id" required>
            </div>
            <input type="submit" value="Buscar" style="margin-top: 0; padding: 12px 24px;">
        </form>
        
        <?php
            if (isset($_GET["busca_id"]) && $_GET["busca_id"] != "") {
                $busca = $_GET["busca_id"];
                if (file_exists("perguntas.txt")) {
                    $arq = fopen("perguntas.txt", "r");
                    echo "<br><b>Resultado da Busca:</b><br>";
                    while(!feof($arq)) {
                        $linha = fgets($arq);
                        if (trim($linha) != "") {
                            $dados = explode(";", trim($linha));
                            if (isset($dados[0]) && $dados[0] == $busca) {
                                echo "ID: " . $dados[0] . " | Tipo: " . $dados[1] . " | Pergunta: " . $dados[2] . " | Opções: " . $dados[3] . " | Resposta: " . $dados[4] . "<br>";
                            }
                        }
                    }
                    fclose($arq);
                }
            }
        ?>

        <h1>5. Listar Perguntas e Respostas</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Pergunta</th>
                <th>Opções</th>
                <th>Resposta</th>
            </tr>
            <?php
                if(file_exists("perguntas.txt")) {
                    $arq = fopen("perguntas.txt", "r");
                    while(!feof($arq)) {
                        $linha = fgets($arq);
                        if (trim($linha) != "") {
                            $dados = explode(";", trim($linha));
                            if (count($dados) >= 5) {
                                echo "<tr>";
                                echo "<td>" . $dados[0] . "</td>";
                                echo "<td>" . $dados[1] . "</td>";
                                echo "<td>" . $dados[2] . "</td>";
                                echo "<td>" . $dados[3] . "</td>";
                                echo "<td>" . $dados[4] . "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    fclose($arq);
                }
            ?>
        </table>

    </div>

</body>
</html>