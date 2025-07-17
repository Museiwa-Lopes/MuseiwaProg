<?php
session_start();

// Se não houver mensagem de sucesso na sessão, redireciona
if (!isset($_SESSION['mensagem']) || $_SESSION['tipo_mensagem'] !== "sucesso") {
    header("Location: listar_ocorrencias.php");
    exit();
}

$mensagem = $_SESSION['mensagem'];
unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']); // Limpa a sessão
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>✅ Sucesso - PRM Vilankulo</title>
    <meta http-equiv="refresh" content="5;url=listar_ocorrencias.php">
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-image: url('/image/PRM.jpeg');
            background-repeat: round;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            background: #48ed;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Times New Roman', Times, serif;
            border: 3px solid rgb(24, 198, 11);
            animation: fadeIn 1s ease-in-out;
        }

        .emoji {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .success-message {
            color:rgb(255, 255, 255);
            border: 1px solid rgb(24, 198, 11);
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            font-family: Arial, sans-serif;
            box-shadow: 0px 0px 5px rgba(216, 0, 12, 0.5);
            animation: fadeIn 0.5s ease-in-out;
            font-family: 'Times New Roman', Times, serif;
        }

        .redirect-message {
            font-size: 14px;
            color: #333;
            margin-top: 10px;
        }

        .button {
            background-color: rgb(55, 142, 65);
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            border: 2px solid #ffffff;
            margin-top: 10px;
            transition: background-color 0.3s ease-in-out;
        }

        .button:hover {
            background-color: rgba(11, 2, 53, 0.8);
        }

        /* Animação de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">✅📨</div>
        <div class="success-message"><?= htmlspecialchars($mensagem) ?></div>
        <a href="listar_ocorrencias.php" class="button">🔙 Voltar à Lista</a>
        <div class="redirect-message">Serás redireccionado automaticamente em 5 segundos...</div>
    </div>
</body>
</html>

