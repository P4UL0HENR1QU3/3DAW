<?php
    if (isset($_REQUEST["acao"])) {
        $conn = new mysqli("localhost", "root", "", "bd_jogo_waterfalls");
        if ($conn->connect_error) {
            die(json_encode(["status" => "erro", "mensagem" => "Falha de conexão com o banco de dados."]));
        }
        $conn->set_charset("utf8mb4");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
        header('Content-Type: application/json');
        $mensagem = "";

        if ($_POST["acao"] == "Cadastrar Usuario") {
            $nome = $_POST["nome_usuario"] ?? "";
            $email = $_POST["email_usuario"] ?? "";

            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $nome, $email);
            
            if ($stmt->execute()) {
                $mensagem = "Usuário salvo com sucesso no banco de dados!";
            } else {
                $mensagem = "Erro ao salvar usuário.";
            }
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

            $stmt = $conn->prepare("INSERT INTO perguntas (id, tipo, pergunta, opcoes, resposta) VALUES (?, 'Multipla', ?, ?, ?)");
            $stmt->bind_param("isss", $id, $pergunta, $opcoes, $resposta);
            
            if ($stmt->execute()) {
                $mensagem = "Pergunta de múltipla escolha salva!";
            } else {
                $mensagem = "Erro: Este ID já existe no banco.";
            }
        }

        if ($_POST["acao"] == "Incluir Texto") {
            $id = $_POST["id"] ?? "";
            $pergunta = $_POST["pergunta"] ?? "";
            $resposta = $_POST["resposta"] ?? "";
            $opcoes = "-";

            $stmt = $conn->prepare("INSERT INTO perguntas (id, tipo, pergunta, opcoes, resposta) VALUES (?, 'Texto', ?, ?, ?)");
            $stmt->bind_param("isss", $id, $pergunta, $opcoes, $resposta);
            
            if ($stmt->execute()) {
                $mensagem = "Pergunta de texto salva!";
            } else {
                $mensagem = "Erro: Este ID já existe no banco.";
            }
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

            $stmt = $conn->prepare("UPDATE perguntas SET pergunta = ?, opcoes = ?, resposta = ? WHERE id = ? AND tipo = 'Multipla'");
            $stmt->bind_param("sssi", $pergunta, $opcoes, $resposta, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $mensagem = "Pergunta de múltipla escolha alterada com sucesso!";
            } else {
                $mensagem = "Erro: Pergunta não encontrada ou não houve alteração.";
            }
        }

        if ($_POST["acao"] == "Alterar Texto") {
            $id = $_POST["id"] ?? "";
            $pergunta = $_POST["pergunta"] ?? "";
            $resposta = $_POST["resposta"] ?? "";

            $stmt = $conn->prepare("UPDATE perguntas SET pergunta = ?, resposta = ? WHERE id = ? AND tipo = 'Texto'");
            $stmt->bind_param("ssi", $pergunta, $resposta, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $mensagem = "Pergunta de texto alterada com sucesso!";
            } else {
                $mensagem = "Erro: Pergunta não encontrada ou não houve alteração.";
            }
        }

        if ($_POST["acao"] == "Excluir") {
            $id = $_POST["id"] ?? "";

            $stmt = $conn->prepare("DELETE FROM perguntas WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $mensagem = "Pergunta excluída com sucesso!";
            } else {
                $mensagem = "Erro: Pergunta não encontrada.";
            }
        }

        echo json_encode(["status" => "sucesso", "mensagem" => $mensagem]);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["acao"])) {
        header('Content-Type: application/json');

        if ($_GET["acao"] == "listar") {
            $result = $conn->query("SELECT id, tipo, pergunta, opcoes, resposta FROM perguntas");
            $lista = $result->fetch_all(MYSQLI_NUM);
            echo json_encode($lista);
            exit;
        }

        if ($_GET["acao"] == "buscar") {
            $busca = $_GET["busca_id"] ?? "";
            $stmt = $conn->prepare("SELECT id, tipo, pergunta, opcoes, resposta FROM perguntas WHERE id = ?");
            $stmt->bind_param("i", $busca);
            $stmt->execute();
            $result = $stmt->get_result();
            $resultado = $result->fetch_row();
            
            echo json_encode($resultado);
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Jogo de Perguntas e Respostas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">

        <div id="alerta-box" class="alerta" style="display: none;"></div>

        <h1>Cadastro de Usuários</h1>
        <form id="form-usuario">
            <input type="hidden" name="acao" value="Cadastrar Usuario">
            <label>Nome do Gestor:</label>
            <input type="text" name="nome_usuario" required>

            <label>E-mail:</label>
            <input type="text" name="email_usuario" required>

            <input type="submit" value="Cadastrar Usuario">
        </form>

        <h1>1. Incluir Pergunta de Múltipla Escolha</h1>
        <form id="form-incluir-multipla">
            <input type="hidden" name="acao" value="Incluir Multipla">
            <label>ID:</label>
            <input type="number" name="id" required>

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

            <input type="submit" value="Incluir Múltipla">
        </form>

        <h1>2. Incluir Pergunta de Texto</h1>
        <form id="form-incluir-texto">
            <input type="hidden" name="acao" value="Incluir Texto">
            <label>ID:</label>
            <input type="number" name="id" required>

            <label>Pergunta:</label>
            <input type="text" name="pergunta" required>

            <label>Resposta:</label>
            <input type="text" name="resposta" required>

            <input type="submit" value="Incluir Texto">
        </form>

        <h1>3. Alterar Múltipla Escolha</h1>
        <form id="form-alterar-multipla">
            <input type="hidden" name="acao" value="Alterar Multipla">
            <label>ID p/ Alterar:</label>
            <input type="number" name="id" required>

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

            <input type="submit" value="Alterar Múltipla">
        </form>

        <h1>4. Alterar Texto</h1>
        <form id="form-alterar-texto">
            <input type="hidden" name="acao" value="Alterar Texto">
            <label>ID p/ Alterar:</label>
            <input type="number" name="id" required>

            <label>Nova Pergunta:</label>
            <input type="text" name="pergunta" required>

            <label>Nova Resposta:</label>
            <input type="text" name="resposta" required>

            <input type="submit" value="Alterar Texto">
        </form>

        <h1>7. Excluir Pergunta e Respostas</h1>
        <form id="form-excluir">
            <input type="hidden" name="acao" value="Excluir">
            <label>ID p/ Excluir:</label>
            <input type="number" name="id" required>

            <input type="submit" value="Excluir">
        </form>

        <h1>6. Listar uma Pergunta</h1>
        <form id="form-buscar" style="flex-direction: row; align-items: flex-end;">
            <div style="flex-grow: 1; display: flex; flex-direction: column; gap: 12px;">
                <label>Buscar por ID:</label>
                <input type="number" name="busca_id" id="busca_id" required>
            </div>
            <input type="submit" value="Buscar" style="margin-top: 0; padding: 12px 24px;">
        </form>

        <div id="resultado-busca" style="margin-top: 15px;"></div>

        <h1>5. Listar Perguntas e Respostas</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Pergunta</th>
                    <th>Opções</th>
                    <th>Resposta</th>
                </tr>
            </thead>
            <tbody id="tabela-perguntas">
            </tbody>
        </table>

    </div>

    <script>
        function mostrarAlerta(mensagem) {
            const alerta = document.getElementById('alerta-box');
            alerta.innerHTML = mensagem;
            alerta.style.display = 'block';

            setTimeout(() => {
                alerta.style.display = 'none';
            }, 3000);
        }

        function carregarTabela() {
            fetch('?acao=listar')
                .then(response => response.json())
                .then(dados => {
                    const tbody = document.getElementById('tabela-perguntas');
                    tbody.innerHTML = "";

                    dados.forEach(pergunta => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${pergunta[0]}</td>
                                <td>${pergunta[1]}</td>
                                <td>${pergunta[2]}</td>
                                <td>${pergunta[3]}</td>
                                <td>${pergunta[4]}</td>
                            </tr>
                        `;
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            carregarTabela();

            const formularios = document.querySelectorAll('form');

            formularios.forEach(form => {
                form.addEventListener('submit', function(evento) {
                    evento.preventDefault();

                    if (this.id === 'form-buscar') {
                        const idBusca = document.getElementById('busca_id').value;
                        fetch(`?acao=buscar&busca_id=${idBusca}`)
                            .then(response => response.json())
                            .then(dados => {
                                const divResultado = document.getElementById('resultado-busca');
                                if (dados) {
                                    divResultado.innerHTML = `<b>Resultado da Busca:</b><br>ID: ${dados[0]} | Tipo: ${dados[1]} | Pergunta: ${dados[2]} | Opções: ${dados[3]} | Resposta: ${dados[4]}`;
                                } else {
                                    divResultado.innerHTML = `<b style="color:red;">Pergunta não encontrada.</b>`;
                                }
                            });
                        return;
                    }

                    const dadosFormulario = new FormData(this);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: dadosFormulario
                    })
                    .then(response => response.json())
                    .then(respostaPHP => {
                        mostrarAlerta(respostaPHP.mensagem);
                        this.reset();
                        carregarTabela();
                    })
                    .catch(erro => console.error("Ocorreu um erro:", erro));
                });
            });
        });
    </script>
</body>
</html>
