<?php
session_start();
include '../includes/conexao.php';

// Verifica se o cidadão está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo_usuario'] !== 'Cidadao') {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$msg = '';

// Carrega dados atuais
$stmt = $conn->prepare("SELECT * FROM tbl_cidadao WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='error-message'>Erro ao carregar dados do cidadão.</div>";
    exit();
}

// Se o formulário for submetido
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome_cidadao'] ?? '';
    $contacto = $_POST['contacto_cidadao'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';

    try {
        if (!empty($novaSenha)) {
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $sql = "UPDATE tbl_cidadao SET nome_cidadao = ?, contacto_cidadao = ?, senha = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nome, $contacto, $senhaHash, $id]);
        } else {
            $sql = "UPDATE tbl_cidadao SET nome_cidadao = ?, contacto_cidadao = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nome, $contacto, $id]);
        }
        $msg = "<div class='success-message'>Conta atualizada com sucesso!</div>";
        // Atualiza os dados exibidos sem recarregar a página
        $user['nome_cidadao'] = $nome;
        $user['contacto_cidadao'] = $contacto;
    } catch (PDOException $e) {
        $msg = "<div class='error-message'>Erro: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Actualizar conta | MuseiwaProg</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-image: url(/image/PRM.jpeg);
            background-repeat: round;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            color: #fff;
            background-color: #48ed;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            width: 400px;
            max-width: 98vw;
            min-height: 340px;
            padding: 30px 20px 20px 20px;
            margin: 30px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #fff;
        }

        .container h2 {
            margin-bottom: 18px;
            font-size: 1.6em;
            text-align: center;
            color: #fff;
        }

        .container form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container input {
            background-color: #eee;
            border: 3px solid #1e00ff;
            margin: 10px 0;
            padding: 10px 15px;
            font-size: 15px;
            border-radius: 8px;
            width: 100%;
            outline: none;
            color: #222;
            transition: border 0.3s;
        }
        .container input:focus {
            border: 3px solid #378e41;
        }

        .container button {
            background-color: rgb(55, 142, 65);
            color: #fff;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border: 2px solid #fff;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.4s, transform 0.2s;
        }

        .container button:hover {
            background-color: rgba(11, 2, 53, 0.718);
            transform: scale(1.03);
        }

        .voltar {
            display: block;
            background-color: #378e41;
            color: #fff;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border: 2px solid #fff;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.4s;
        }

        .voltar:hover {
            background-color: #1b2e53;
        }

        .error-message, .success-message {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-family: Georgia, 'Times New Roman', Times, serif;
            animation: fadeIn 0.5s ease-in-out;
            font-size: 15px;
        }

        .error-message {
            background-color: #ffdddd;
            color: #d8000c;
            border: 2px solid #d8000c;
        }

        .success-message {
            background-color: #ddffdd;
            color: #008000;
            border: 2px solid #008000;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media screen and (max-width: 570px) {
            body {
                background-image: url(/image/PRMmobile.jpeg);
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                min-height: 100vh;
                height: auto;
                padding: 0;
            }
            .container {
                width: 98vw !important;
                min-width: unset !important;
                max-width: 99vw !important;
                padding: 10px 2vw !important;
                border-radius: 15px !important;
                margin: 10px auto !important;
            }
            .container h2 {
                font-size: 1.2em;
            }
            .container input, .container button, .voltar {
                font-size: 17px !important;
                width: 100% !important;
            }
            .error-message, .success-message {
                font-size: 14px !important;
                padding: 8px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Actualizar conta</h2>
        <?= $msg ?>
        <form method="POST">
            <input type="text" name="nome_cidadao" placeholder="Nome completo" value="<?= htmlspecialchars($user['nome_cidadao']) ?>" required>
            <input type="text" name="contacto_cidadao" placeholder="Contacto" value="<?= htmlspecialchars($user['contacto_cidadao']) ?>" required>
            <input type="password" name="nova_senha" placeholder="Nova senha (deixe em branco se não quiser alterar)">
            <button type="submit">Actualizar</button>
            <a href="registar_ocorrencia.php" class="voltar">⬅️ Voltar</a>
        </form>
    </div>
</body>
</html>
