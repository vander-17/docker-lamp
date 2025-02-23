<?php
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

// Processa o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    // Validação básica dos campos
    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        // Prepara a consulta SQL para evitar SQL injection
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o usuário existe
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($senha, $usuario["senha"])) {
                // Inicia a sessão e redireciona para o dashboard
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nome"] = $usuario["nome"];
                header("Location: dashboard.php");
                exit();
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }

        // Fecha a declaração preparada
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
    <title>Login - Hotel Paraíso do Tocantins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #1abc9c;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-container button:hover {
            background-color: #16a085;
        }
        .erro {
            color: red;
            margin-bottom: 10px;
        }
        .link-cadastro {
            margin-top: 15px;
            font-size: 14px;
        }
        .link-cadastro a {
            color: #1abc9c;
            text-decoration: none;
        }
        .link-cadastro a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($erro)): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <div class="link-cadastro">
            Não tem uma conta? <a href="register.php">Cadastre-se aqui</a>
        </div>
    </div>
</body>
</html>