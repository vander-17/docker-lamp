<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e se a cidade está na sessão
if (isset($_SESSION["usuario_nome"]) && !isset($_SESSION["cidade"])) {
    // Se a cidade não estiver na sessão, você pode buscá-la do banco de dados
    // Aqui, vamos simular que a cidade foi cadastrada como "Limoeiro do Ajuru/PA"
    $_SESSION["cidade"] = "Limoeiro do Ajuru/PA"; // Substitua por uma consulta ao banco de dados
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paraíso do Tocantins</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <!-- Ícone de localização (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: rgb(31, 102, 173);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative; /* Permite posicionar elementos filhos */
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: rgb(46, 112, 179);
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
        .banner {
            text-align: center;
            padding: 100px 20px;
            background: url('banner.jpg') no-repeat center center/cover;
            color: white;
            position: relative;
        }
        .banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .banner h2, .banner p, .banner button {
            position: relative;
            z-index: 1;
        }
        .banner h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .banner p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .button {
            padding: 15px 25px;
            font-size: 18px;
            color: white;
            background-color: #1abc9c;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #16a085;
        }
        .content {
            text-align: center;
            padding: 50px 20px;
            background-color: white;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .content h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .carrossel {
            margin: 20px auto;
            max-width: 800px;
        }
        .carrossel img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .slick-prev:before,
        .slick-next:before {
            color: #1abc9c;
        }
        .slick-dots li button:before {
            color: #1abc9c;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        footer p {
            margin: 0;
        }
        @media (max-width: 768px) {
            .banner {
                padding: 60px 20px;
            }
            .banner h2 {
                font-size: 2em;
            }
            .banner p {
                font-size: 1em;
            }
            .button {
                font-size: 16px;
                padding: 10px 20px;
            }
            .content {
                padding: 30px 15px;
            }
            .content h2 {
                font-size: 1.5em;
            }
        }
        /* Estilo para o nome do usuário e link de sair */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-info a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .user-info a:hover {
            background-color: #1abc9c;
        }
        /* Estilo para a seleção de cidade no canto superior direito */
        .cidade-topo {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .cidade-topo i {
            font-size: 18px;
        }
        .cidade-topo select {
            background: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .cidade-topo select option {
            color: #333;
        }
    </style>
</head>
<body>

<header>
    <h1>Bem-vindo ao Hotel Paraíso do Tocantins</h1>
    <?php if (isset($_SESSION["usuario_nome"])): ?>
        <!-- Exibe a seleção de cidade no canto superior direito -->
        <div class="cidade-topo" title="Alterar cidade">
            <i class="fas fa-map-marker-alt"></i>
            <select id="cidade" onchange="alterarCidade(this)">
                <option value="Limoeiro do Ajuru/PA" <?php echo ($_SESSION["cidade"] == "Limoeiro do Ajuru/PA") ? "selected" : ""; ?>>Limoeiro do Ajuru/PA</option>
                <option value="Cametá/PA" <?php echo ($_SESSION["cidade"] == "Cametá/PA") ? "selected" : ""; ?>>Cametá/PA</option>
            </select>
        </div>
    <?php endif; ?>
</header>

<nav>
    <a href="index.php">Início</a>
    <a href="sobre.php">Sobre</a>
    <a href="contato.php">Contato</a>
    <?php if (isset($_SESSION["usuario_nome"])): ?>
        <!-- Exibe o nome do usuário e o link de sair -->
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?></span>
            <a href="logout.php">Sair</a>
        </div>
    <?php else: ?>
        <!-- Exibe os links de cadastro e login -->
        <a href="register.php">Cadastro</a>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>

<div class="banner">
    <h2>Hospedagem de luxo para você</h2>
    <p>Reserve já o seu quarto!</p>
    <?php if (!isset($_SESSION["usuario_nome"])): ?>
        <button class="button" onclick="window.location.href='register.php'">Cadastre-se</button>
    <?php endif; ?>
</div>

<div class="content">
    <h2>Conheça nossos quartos</h2>
    <div class="carrossel">
        <div><img src="apartamento.jpg" alt="Apartamento"></div>
        <div><img src="solteiro.jpg" alt="Quarto de Solteiro"></div>
        <div><img src="casal.jpg" alt="Quarto de Casal"></div>
    </div>
</div>

<footer>
    <p>&copy; 2025 Hotel Paraíso do Tocantins. Todos os direitos reservados.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.carrossel').slick({
            dots: true,          // Mostra os pontos de navegação
            infinite: true,     // Loop infinito
            speed: 300,         // Velocidade da transição
            slidesToShow: 1,    // Quantidade de slides visíveis
            adaptiveHeight: true, // Ajusta a altura automaticamente
            autoplay: true,     // Habilita o movimento automático
            autoplaySpeed: 3000 // Tempo entre cada transição (3 segundos)
        });
    });

    // Função para alterar a cidade
    function alterarCidade(select) {
        const cidade = select.value;
        fetch('alterar_cidade.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cidade })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cidade alterada com sucesso!');
                location.reload(); // Recarrega a página para atualizar a cidade
            } else {
                alert('Erro ao alterar a cidade.');
            }
        })
        .catch(error => console.error('Erro:', error));
    }
</script>

</body>
</html>