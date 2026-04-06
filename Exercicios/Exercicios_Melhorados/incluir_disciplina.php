<?php
    $mensagem = "";

    if (isset($_POST["matricula"])) {
        $matricula = $_POST["matricula"];
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $disciplina = $_POST["disciplina"];

        if ($matricula != "" && $nome != "" && $email != "" && $disciplina != "") {

            $arqAluno = fopen("alunos.txt", "a");

            $novaLinha = "\n" . $matricula . ";" . $nome . ";" . $email . ";" . $disciplina;

            fwrite($arqAluno, $novaLinha);
            fclose($arqAluno);

            $mensagem = "Aluno salvo com sucesso!";
        } else {
            $mensagem = "Ops! Preencha todos os campos.";
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
        Disciplina: <input type="text" name="disciplina"><br><br>
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
            <th>Disciplina</th>
        </tr>
        <?php
            $arqAluno = fopen("alunos.txt", "r");

            while(!feof($arqAluno)) {
                $linha = fgets($arqAluno);

                if ($linha != "") {
                    $colunaDados = explode(";", $linha);

                    echo "<tr>";
                    echo "<td>" . $colunaDados[0] . "</td>";
                    echo "<td>" . $colunaDados[1] . "</td>";
                    echo "<td>" . $colunaDados[2] . "</td>";
                    
                    if (isset($colunaDados[3])) {
                        echo "<td>" . $colunaDados[3] . "</td>";
                    } else {
                        echo "<td></td>";
                    }
                    
                    echo "</tr>";
                }
            }

            fclose($arqAluno);
        ?>
    </table>

</body>
</html>
