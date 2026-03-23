<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $operador = $_POST["operador"];
        $resultado = 0;
        
        if ($operador == "soma") {
            $resultado = $a + $b;
        } elseif ($operador == "sub") {
            $resultado = $a - $b;
        } elseif ($operador == "multi") {
            $resultado = $a * $b;
        } elseif ($operador == "divide") {
            $resultado = $a / $b;
        } else {
            $erro = "operador não definido";
        }
    }
?>
<!DOCTYPE html>
<html>
<body>
<h1><?php echo 'Minha Calculadora!'; ?></h1>

<form method='POST' action='calculadora.php'>
    a:<input type=text name='a'><br>
    b:<input type=text name='b'>
    <br>Operação: 
    <br><input type="radio" name="operador" value="soma"> Soma
    <br><input type="radio" name="operador" value="sub"> Subtrai
    <br><input type="radio" name="operador" value="multi"> Multiplica
    <br><input type="radio" name="operador" value="divide"> Divide
    <br>
    <input type=submit value='Calcular'>
    <br><br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo 'Resultado: ' . $resultado; 
    }
    ?>
    
</body>
</html>