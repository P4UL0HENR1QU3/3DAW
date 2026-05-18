<?php
    $resultado = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $operador = $_POST["operador"];
        
        if ($operador == "soma") {
            $resultado = $a + $b;
        } elseif ($operador == "sub") {
            $resultado = $a - $b;
        } elseif ($operador == "multi") {
            $resultado = $a * $b;
        } elseif ($operador == "divide") {
            $resultado = $a / $b;
        } else {
            $resultado = "Operador não definido";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Calculadora Web</title>
    <style>
        /* Estilo do fundo da página */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        /* O "Cartão" da Calculadora */
        .calculadora-container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
        }

        h1 {
            text-align: center;
            font-size: 1.6em;
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        /* Estilo das caixas de número */
        .campo-input {
            margin-bottom: 15px;
        }

        .campo-input label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .campo-input input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s;
        }

        .campo-input input[type="number"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        /* Estilo das opções de operação */
        .campo-operacao {
            margin-bottom: 25px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .campo-operacao p {
            margin-top: 0;
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }

        .radio-opcao {
            display: block;
            margin-bottom: 8px;
            cursor: pointer;
            color: #444;
        }

        /* Botão de Calcular */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        input[type="submit"]:active {
            transform: scale(0.98);
        }

        /* Caixa de Resultado */
        .caixa-resultado {
            margin-top: 20px;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Pequena animação para o resultado aparecer suavemente */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    <script>
        function validarFormulario() {
            var a = document.getElementById("a").value;
            var b = document.getElementById("b").value;
            var operador = document.querySelector('input[name="operador"]:checked');

            if (a === "" || b === "") {
                alert("Por favor, preencha os campos A e B com números.");
                return false; 
            }

            if (!operador) {
                alert("Por favor, escolha uma operação matemática.");
                return false;
            }

            if (operador.value === "divide" && b === "0") {
                alert("Não é possível realizar divisão por zero.");
                return false;
            }

            return true; 
        }
    </script>
</head>
<body>

    <div class="calculadora-container">
        <h1>Minha Calculadora!</h1>

        <form method="POST" action="" onsubmit="return validarFormulario()">
            
            <div class="campo-input">
                <label for="a">Valor A:</label>
                <input type="number" step="any" name="a" id="a" placeholder="Digite o 1º número">
            </div>
            
            <div class="campo-input">
                <label for="b">Valor B:</label>
                <input type="number" step="any" name="b" id="b" placeholder="Digite o 2º número">
            </div>
            
            <div class="campo-operacao">
                <p>Operação:</p>
                <label class="radio-opcao"><input type="radio" name="operador" value="soma"> + Soma</label>
                <label class="radio-opcao"><input type="radio" name="operador" value="sub"> - Subtração</label>
                <label class="radio-opcao"><input type="radio" name="operador" value="multi"> - Multiplicação</label>
                <label class="radio-opcao"><input type="radio" name="operador" value="divide"> / Divisão</label>
            </div>
            
            <input type="submit" value="Calcular">
        </form>
        
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && $resultado !== "") {
                echo '<div class="caixa-resultado">Resultado: ' . $resultado . '</div>'; 
            }
        ?>
    </div>

</body>
</html>