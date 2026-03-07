<?php
// http://localhost/EXERCICIOS_DAW/Exercicios/Exercicios_Melhorados/calculadora.php

    $resultado = "";
    $a_salvo = "";
    $b_salvo = "";
    $op_salvo = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $operador = $_POST["operador"]; 
        
        $a_salvo = $a;
        $b_salvo = $b;
        $op_salvo = $operador;

        if ($operador == "sqrt") {
            if ($a != "") {
                if ($a < 0) {
                    $resultado = "Erro: Raiz de número negativo!";
                } else {
                    $resultado = sqrt($a);
                }
            } else {
                $resultado = "Preencha o 1º campo para a raiz.";
            }
        } else {
            if ($a != "" && $b != "") {
                if ($operador == "+") {
                    $resultado = $a + $b;
                } elseif ($operador == "-") {
                    $resultado = $a - $b;
                } elseif ($operador == "*") {
                    $resultado = $a * $b;
                } elseif ($operador == "/") {
                    if ($b == 0) {
                        $resultado = "Não é possível dividir por zero";
                    } else {
                        $resultado = $a / $b;
                    }
                } elseif ($operador == "^") {
                    $resultado = pow($a, $b);
                }
            } else {
                $resultado = "Preencha os dois campos.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Calculadora PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px; 
        }
        input[type="text"], select {
            padding: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 5px 15px;
            font-size: 16px;
            cursor: pointer;
        }
        .mensagem-resultado {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .dica {
            font-size: 13px;
            color: #555;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h1>Minha Calculadora!</h1>

<form method='POST' action=''>
    <input type="text" name="a" size="5" placeholder="Nº 1" value="<?php echo $a_salvo; ?>">
    
    <select name="operador">
        <option value="+" <?php if($op_salvo == '+') echo 'selected'; ?>>+</option>
        <option value="-" <?php if($op_salvo == '-') echo 'selected'; ?>>-</option>
        <option value="*" <?php if($op_salvo == '*') echo 'selected'; ?>>*</option>
        <option value="/" <?php if($op_salvo == '/') echo 'selected'; ?>>/</option>
        <option value="^" <?php if($op_salvo == '^') echo 'selected'; ?>>^</option>
        <option value="sqrt" <?php if($op_salvo == 'sqrt') echo 'selected'; ?>>√</option>
    </select>
    
    <input type="text" name="b" size="5" placeholder="Nº 2" value="<?php echo $b_salvo; ?>">
    
    <input type="submit" value="Calcular">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($resultado != "") {
        echo '<div class="mensagem-resultado">Resultado: ' . $resultado . '</div>'; 
    }
}
?>
<div class="dica">*Para raiz quadrada (√), o cálculo usa apenas o 1º campo.</div>

</body>
</html>