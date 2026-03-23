<?php
    $resultado = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $operador = $_POST["operador"];

        if ($operador == "+") {
            $resultado = $a + $b;
        } elseif ($operador == "-") {
            $resultado = $a - $b;
        } elseif ($operador == "*") {
            $resultado = $a * $b;
        } elseif ($operador == "/") {
            // Agora sim o truque funciona!
            if ($b == 0) {
                $resultado = "Erro: não dá para dividir por zero!";
            } else {
                $resultado = $a / $b;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<body>
<h1><?php echo 'Minha Calculadora!';?></h1>

<form method='POST' action=''>
    <input type="text" name="a" size="5">
    
    <select name="operador">
        <option value="+">+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
    </select>
    
    <input type="text" name="b" size="5">
    
    <input type="submit" value="Calcular">
    <br><br>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo 'Resultado: ' . $resultado; 
    }
    ?>
    
</body>
</html>