<?php
session_start();
// Opcional: obter o BI da URL, se quiser usar para o botão de "Visualizar"
$bi = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Ocorrência bem sucedida - PRM | MuseiwaProg</title>
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-image: url(/image/PRM.jpeg);
            background-repeat: round;
            background-size: cover;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .success-container {
            background: #48ed;
            padding: 40px 30px;
            border-radius: 30px;
            box-shadow: 0 0 18px rgba(0, 0, 0, 0.35);
            text-align: center;
            max-width: 420px;
            width: 95vw;
            margin: 20px;
            animation: fadeIn 0.7s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px);}
            to { opacity: 1; transform: translateY(0);}
        }

        .success-container h1 {
            font-size: 1.6em;
            margin-bottom: 18px;
            color: #fff;
            font-weight: bold;
            text-shadow: 1px 1px 6px #00000033;
        }

        .success-container p {
            font-size: 1.1em;
            margin-bottom: 28px;
            color: #f3f3f3;
        }

        .button-group {
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .button-group a {
            display: inline-block;
            background: rgb(55, 142, 65);
            color: #fff;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 8px;
            border: 2px solid #fff;
            font-weight: 600;
            font-size: 1em;
            transition: background 0.3s, color 0.3s, transform 0.2s;
            box-shadow: 0 2px 8px #00000022;
        }

        .button-group a:hover {
            background: #1b2e53;
            color: #ffe;
            transform: translateY(-2px) scale(1.04);
        }

        @media screen and (max-width: 570px) {
            body {
                background-image: url(/image/PRMmobile.jpeg);
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                padding: 0;
            }
            .success-container {
                padding: 18px 5vw;
                max-width: 99vw;
                border-radius: 18px;
                margin: 8px;
            }
            .success-container h1 {
                font-size: 1.1em;
            }
            .success-container p {
                font-size: 0.98em;
            }
            .button-group a {
                width: 100%;
                margin: 6px 0;
                font-size: 1em;
                padding: 10px 0;
            }
            .button-group {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>

<div class="success-container">
    <h1>✅ Ocorrência registada com sucesso!</h1>
    <p>A sua denúncia foi registada no sistema da PRM Vilankulo.</p>

    <div class="button-group">
        <?php if (isset($_SESSION['contacto_cidadao'])): ?>
            <a href="listar_ocorrencias.php?id=<?php echo urlencode($_SESSION['contacto_cidadao']); ?>">🔍Visualizar</a>
        <?php endif; ?>
        <a href="/index.php">🏠Página inicial</a>
    </div>
</div>

</body>
</html>
