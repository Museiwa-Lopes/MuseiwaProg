<?php
include '../includes/conexao.php';
session_start();

if (!isset($_SESSION["tipo_usuario"]) || ($_SESSION["tipo_usuario"] !== "Admin" && $_SESSION["tipo_usuario"] !== "Admin_Master")) {
    header("Location: ../logout.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
} elseif (isset($_SESSION["id_usuario"])) {
    $id_usuario = $_SESSION["id_usuario"];
} else {
    die("ID do usuário não especificado.");
}

$success_message = "";
$error_message = "";

// Buscar dados actuais
$sql = "SELECT * FROM tbl_usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Usuário não encontrado.");
}

// Processar formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $nickname = $_POST["nickname"];
    $gender = $_POST["gender"];
    $badge_number = $_POST["badge-number"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    if ($password !== $confirm_password) {
        $error_message = "As senhas não coincidem!";
    } else {
        // Verifica se distintivo pertence a outro utilizador
        $check_query = "SELECT COUNT(*) FROM tbl_usuarios WHERE distintivo = ? AND id != ?";
        $stmt_check = $conn->prepare($check_query);
        $stmt_check->execute([$badge_number, $id_usuario]);

        if ($stmt_check->fetchColumn() > 0) {
            $error_message = "O número de distintivo já está em uso!";
        } else {
            try {
                $sql_update = "UPDATE tbl_usuarios SET nome = ?, apelido = ?, genero = ?, distintivo = ?";
                $params = [$name, $nickname, $gender, $badge_number];

                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql_update .= ", senha = ?";
                    $params[] = $hashed_password;
                }

                $sql_update .= " WHERE id = ?";
                $params[] = $id_usuario;

                $stmt_update = $conn->prepare($sql_update);
                if ($stmt_update->execute($params)) {
                    $success_message = "Perfil actualizado com sucesso!";
                    // Recarrega os dados
                    $stmt = $conn->prepare("SELECT * FROM tbl_usuarios WHERE id = ?");
                    $stmt->execute([$id_usuario]);
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $error_message = "Erro ao actualizar o perfil.";
                }
            } catch (PDOException $e) {
                $error_message = "Erro: " . $e->getMessage();
            }
        }
    }
}
$conn = null;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar perfil - PRM | MuseiwaProg</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
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
            background-size: cover;
            min-height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 430px;
            width: 98vw;
            margin: 0 auto;
            padding: 28px 18px 18px 18px;
            background-color: #48ed;
            box-shadow: 0 0 10px rgb(59, 58, 58);
            border-radius: 30px;
            border: 2px solid #fff;
            transition: .5s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container:hover {
            transform: scale(1.03);
        }

        h3 {
            color: #fff;
            text-align: center;
            margin-bottom: 18px;
            font-size: 1.7em;
            text-shadow: 0 2px 8px #0008;
        }

        label {
            color: #fff;
            display: block;
            margin-bottom: 8px;
            font-size: 1em;
        }

        input[type="text"],
        input[type="password"],
        select {
            font-family: 'Times New Roman', Times, serif;
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 16px;
            border: 3px solid #1e00ff;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
            font-size: 1em;
            background: #fff;
            color: #222;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            border: 3px solid #378e41;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 4%;
        }

        .form-row > div {
            width: 48%;
        }

        .gender-select option[disabled][selected][hidden] {
            color: #ccc;
            background-color: rgb(51, 51, 51);
        }

        .submit-button {
            background-color: rgb(55, 142, 65);
            color: white;
            display: block;
            padding: 10px 16px;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 12px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            border: 2px solid #fff;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: background 0.4s, transform 0.2s;
        }

        .submit-button:hover {
            background-color: rgba(11, 2, 53, 0.718);
            color: white;
            transform: scale(1.03);
        }

        .container a {
            color: #fff;
            background: #378e41;
            display: inline-block;
            padding: 10px 0;
            width: 100%;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            margin-top: 5px;
            text-decoration: none;
            border: 2px solid #fff;
            transition: background 0.4s;
        }

        .container a:hover {
            background: #1b2e53;
            color: #fff;
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
            border: 1.5px solid #d8000c;
        }

        .success-message {
            background-color: #ddffdd;
            color: #008000;
            border: 1.5px solid #008000;
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
                width: 99vw !important;
                min-width: unset !important;
                max-width: 99vw !important;
                padding: 10px 2vw !important;
                border-radius: 15px !important;
                margin: 10px auto !important;
            }
            h3 {
                font-size: 1.2em;
            }
            input, .submit-button, .container a {
                font-size: 17px !important;
                width: 100% !important;
            }
            .error-message, .success-message {
                font-size: 14px !important;
                padding: 8px !important;
            }
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            .form-row > div {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
<h3>Editar perfil</h3>

<?php
if ($error_message) echo "<div class='error-message'>{$error_message}</div>";
if ($success_message) echo "<div class='success-message'>{$success_message}</div>";
?>

<form method="post">
    <div class="form-row">
        <div>
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($admin['nome']) ?>" required>
        </div>
        <div>
            <label for="nickname">Apelido:</label>
            <input type="text" id="nickname" name="nickname" value="<?= htmlspecialchars($admin['apelido']) ?>" required>
        </div>
    </div>

    <div>
        <label for="gender">Género:</label>
        <select id="gender" name="gender" required>
            <option value="Masculino" <?= $admin['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="Feminino" <?= $admin['genero'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
        </select>
    </div>

    <div>
        <label for="badge-number">N° de distintivo:</label>
        <input type="text" id="badge-number" name="badge-number" maxlength="6" value="<?= htmlspecialchars($admin['distintivo']) ?>" required>
    </div>

    <div class="form-row">
        <div>
            <label for="password">Nova Senha:</label>
            <input type="password" id="password" name="password" maxlength="10" placeholder="Deixe em branco se não quiser mudar">
        </div>
        <div>
            <label for="confirm-password">Confirme a Nova Senha:</label>
            <input type="password" id="confirm-password" name="confirm-password" maxlength="10" placeholder="Repita a senha">
        </div>
    </div>

    <button class="submit-button" type="submit">Actualizar</button>
    <a href="inicio.php">⬅️ Sair</a>
</form>
</div>
</body>
</html>
