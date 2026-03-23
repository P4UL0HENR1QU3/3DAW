<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $soma = $a + $b;
}
      //  echo 'Resultado: ' . $soma;
    //  http://localhost/EXERCICIOS_DAW/Exercicios/Exercicios_Aula/calculadora.php
?>
<!DOCTYPE html>
<html>
<body>
<h1><?php echo 'Minha Calculadora!';?></h1>

<form method='POST' action='calculadora.php'>
    a:<input type=text name='a'><br>
    b:<input type=text name='b'>
    <input type=submit value='Somar'>
    <br><br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo 'Resultado: ' . $soma; 
}
?>
    
</body>
</html>