<?php
// Inicia a sessão
session_start();

// Configurações do banco de dados
$servername = "mysql";
$username = "admin";
$password = "admin";
$dbname = "lampdb";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica erros de conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Processa o formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = trim($_POST["nome"]);
    $sobrenome = trim($_POST["sobrenome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    $confirmar_senha = trim($_POST["confirmar_senha"]);
    $cidade = trim($_POST["cidade"]); // Nova variável para a cidade

    // Validação básica dos campos
    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha) || empty($confirmar_senha) || empty($cidade)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        // Verifica se o e-mail já está cadastrado
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o novo usuário no banco de dados
            $sql = "INSERT INTO usuarios (nome, sobrenome, email, senha, cidade) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nome, $sobrenome, $email, $senha_hash, $cidade);

            if ($stmt->execute()) {
                // Recupera o ID do usuário cadastrado
                $usuario_id = $stmt->insert_id;

                // Armazena os dados do usuário na sessão
                $_SESSION["usuario_id"] = $usuario_id;
                $_SESSION["usuario_nome"] = $nome;

                // Redireciona para o index.php
                header("Location: index.php");
                exit();
            } else {
                $erro = "Erro ao cadastrar: " . $conn->error;
            }
        }
        $stmt->close();
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Hotel Paraíso do Tocantins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: #34495e;
            padding: 10px;
            flex-wrap: wrap;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #1abc9c;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .button {
            width: 100%;
            padding: 10px;
            background-color: #1abc9c;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #16a085;
        }
        .erro {
            color: red;
            margin-bottom: 10px;
        }
        .link-login {
            margin-top: 15px;
            font-size: 14px;
        }
        .link-login a {
            color: #1abc9c;
            text-decoration: none;
        }
        .link-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Cadastro - Hotel Paraíso do Tocantins</h1>
</header>

<nav>
    <a href="index.php">Início</a>
    <a href="sobre.php">Sobre</a>
    <a href="quartos.php">Quartos</a>
    <a href="contato.php">Contato</a>
</nav>

<div class="container">
    <h2>Cadastre-se</h2>
    <?php if (isset($erro)): ?>
        <div class="erro"><?php echo $erro; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="sobrenome" placeholder="Sobrenome" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
        <select name="cidade" required>
            <option value="">Selecione sua cidade</option>
            <option value="Limoeiro do Ajuru/PA">Limoeiro do Ajuru/PA</option>
            <option value="Cametá/PA">Cametá/PA</option>
        </select>
        <button class="button" type="submit">Cadastrar</button>
    </form>
    <div class="link-login">
        Já tem uma conta? <a href="login.php">Faça login aqui</a>
    </div>
</div>

</body>
</html>