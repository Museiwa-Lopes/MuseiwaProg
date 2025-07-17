<?php
session_start();

if (!isset($_SESSION['mensagem']) || !isset($_SESSION['tipo_mensagem'])) {
    header("Location: listar_ocorrencias.php");
    exit();
}

$mensagem = $_SESSION['mensagem'];
$tipo_mensagem = $_SESSION['tipo_mensagem'];

unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso | MuseiwaProg </title>
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
    <meta http-equiv="refresh" content="2;url=listar_ocorrencias.php">
</head>
<body>

<div class="container">
    <h1 class="<?= $tipo_mensagem === 'sucesso' ? 'success-message' : 'error-message' ?>">
        <?= htmlspecialchars($mensagem) ?>
    </h1>
    <!-- <a href="listar_ocorrencias.php" class="button">⬅️Voltar para a lista</a> -->
</div>

</body>
</html>

<style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-image:url(/image/PRM.jpeg);
            background-repeat: round;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            height: 84vh;
            font-family: 'Times New Roman', Times, serif;
            overflow: hidden;
        }
        .container {
            text-align: center;
            background: #48ed;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Times New Roman', Times, serif;
            border: 3px solid rgb(24, 198, 11)
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
        /* Animação de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .button {
            background-color: rgb(55, 142, 65);
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            border: 2px solid rgb(255, 255, 255);
            box-shadow:rgb(0, 0, 0);
            font-weight: 600;
        }
    </style>
