<?php
    $mensagem = "";

    if (isset($_POST["acao"])) {
        
        if ($_POST["acao"] == "Incluir") {
            $matricula = $_POST["matricula"];
            $nome = $_POST["nome"];
            $email = $_POST["email"];

            if ($matricula != "" && $nome != "" && $email != "") {
                $arqAluno = fopen("alunos.txt", "a");
                $novaLinha = $matricula . ";" . $nome . ";" . $email . "\n";
                fwrite($arqAluno, $novaLinha);
                fclose($arqAluno);
                $mensagem = "Aluno incluído com sucesso!";
            } else {
                $mensagem = "Ops! Preencha todos os campos para incluir.";
            }
        }

        if ($_POST["acao"] == "Alterar") {
            $matricula_alt = $_POST["matricula_alt"];
            $nome_alt = $_POST["nome_alt"];
            $email_alt = $_POST["email_alt"];

            if ($matricula_alt != "" && $nome_alt != "" && $email_alt != "") {
                $linhas = file("alunos.txt");
                $arqAluno = fopen("alunos.txt", "w");
                $encontrou = false;

                foreach ($linhas as $linha) {
                    $linhaLimpa = trim($linha);
                    
                    if ($linhaLimpa != "") {
                        $colunaDados = explode(";", $linhaLimpa);
                        
                        if ($colunaDados[0] == $matricula_alt) {
                            $novaLinha = $matricula_alt . ";" . $nome_alt . ";" . $email_alt . "\n";
                            fwrite($arqAluno, $novaLinha);
                            $encontrou = true;
                        } else {
                            fwrite($arqAluno, $linhaLimpa . "\n");
                        }
                    }
                }
                fclose($arqAluno);

                if ($encontrou == true) {
                    $mensagem = "Aluno alterado com sucesso!";
                } else {
                    $mensagem = "Matrícula não encontrada no sistema!";
                }
            } else {
                $mensagem = "Ops! Preencha todos os campos para alterar.";
            }
        }
    }
?>
<html>
<head>
    <title>Sistema de Alunos</title>
</head>
<body>

    <h1>Incluir Novo Aluno</h1>
    <form method="POST" action="">
        Matrícula: <input type="text" name="matricula"><br><br>
        Nome: <input type="text" name="nome"><br><br>
        Email: <input type="text" name="email"><br><br>
        <input type="submit" name="acao" value="Incluir">
    </form>

    <hr>

    <h1>Alterar Aluno Existente</h1>
    <form method="POST" action="">
        Matrícula (Qual aluno?): <input type="text" name="matricula_alt"><br><br>
        Novo Nome: <input type="text" name="nome_alt"><br><br>
        Novo Email: <input type="text" name="email_alt"><br><br>
        <input type="submit" name="acao" value="Alterar">
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
            if(file_exists("alunos.txt")) {
                $arqAluno = fopen("alunos.txt", "r");
            
                while(!feof($arqAluno)) {
                    $linha = fgets($arqAluno);
                    
                    if (trim($linha) != "") {
                        $colunaDados = explode(";", $linha);
                
                        echo "<tr>";
                        echo "<td>" . $colunaDados[0] . "</td>";
                        echo "<td>" . $colunaDados[1] . "</td>";
                        echo "<td>" . $colunaDados[2] . "</td>";
                        echo "</tr>";
                    }
                }
                fclose($arqAluno);
            }
        ?>
    </table>

</body>
</html>