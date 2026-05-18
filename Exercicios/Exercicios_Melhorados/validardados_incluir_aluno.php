<?php
    $mensagem = "";

    if (isset($_POST["matricula"])) {
        // O trim() remove espaços vazios no começo e no fim do que foi digitado
        $matricula = trim($_POST["matricula"]);
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);

        // 1. Verifica se tem algum campo vazio
        if ($matricula == "" || $nome == "" || $email == "") {
            $mensagem = "Ops! Preencha todos os campos.";
        
        // 2. Verifica se a matrícula contém apenas números
        } elseif (!is_numeric($matricula)) {
            $mensagem = "Erro: A matrícula deve conter apenas números.";
            
        // 3. Verifica se o nome tem pelo menos 3 letras
        } elseif (strlen($nome) < 3) {
            $mensagem = "Erro: O nome digitado é muito curto.";
            
        // 4. Verifica se o formato do e-mail é válido
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensagem = "Erro: Digite um e-mail válido (ex: aluno@email.com).";
            
        // Se passar por todas as validações acima, salva o arquivo!
        } else {
            $arqAluno = fopen("alunos.txt", "a");
            
            $novaLinha = "\n" . $matricula . ";" . $nome . ";" . $email;
            
            fwrite($arqAluno, $novaLinha);
            fclose($arqAluno);
            
            $mensagem = "Aluno salvo com sucesso!";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Alunos</title>
</head>
<body>

    <h1>Incluir Novo Aluno</h1>
    
    <form method="POST" action="">
        Matrícula: <input type="text" name="matricula" required><br><br>
        Nome: <input type="text" name="nome" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        <input type="submit" value="Salvar Aluno">
    </form>
    
    <p><b><?php echo $mensagem; ?></b></p>

    <hr>

    <h1>Listar Alunos</h1>

    <table border="1">
        <tr>
            <th>Matrícula</th>
            <th>Nome</th>
            <th>Email</th>
        </tr>
        <?php
            // Verifica se o arquivo existe antes de tentar abrir, evitando erros
            if (file_exists("alunos.txt")) {
                $arqAluno = fopen("alunos.txt", "r");
            
                while(!feof($arqAluno)) {
                    $linha = fgets($arqAluno);
                    
                    if (trim($linha) != "") {
                        $colunaDados = explode(";", $linha);
                
                        // Confirma se a linha tem exatamente 3 informações antes de exibir
                        if (count($colunaDados) >= 3) {
                            echo "<tr>";
                            echo "<td>" . $colunaDados[0] . "</td>";
                            echo "<td>" . $colunaDados[1] . "</td>";
                            echo "<td>" . $colunaDados[2] . "</td>";
                            echo "</tr>";
                        }
                    }
                }
            
                fclose($arqAluno);
            } else {
                echo "<tr><td colspan='3'>Nenhum aluno cadastrado ainda.</td></tr>";
            }
        ?>
    </table>

</body>
</html>