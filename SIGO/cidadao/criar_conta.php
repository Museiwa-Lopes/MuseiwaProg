<?php
session_start();

include '../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_cidadao = trim($_POST['nome_cidadao'] ?? '');
    $contacto_cidadao = trim($_POST['contacto_cidadao'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirma_senha'] ?? '';
    $user_type = "Cidadao";

    // Verificar se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        $erroMensagem = "As senhas não coincidem.";
    } else {
        // Verifica se já existe o nome de utilizador
        $stmt = $conn->prepare("SELECT id FROM tbl_cidadao WHERE nome_cidadao = ?");
        $stmt->execute([$nome_cidadao]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $erroMensagem = "Esse nome de utilizador já está registado.";
        } else {
            // Criptografar a senha
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Inserir novo utilizador
            $stmt = $conn->prepare("INSERT INTO tbl_cidadao (nome_cidadao, contacto_cidadao, senha, tipo_usuario) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nome_cidadao, $contacto_cidadao, $senhaHash, $user_type])) {
                $_SESSION['mensagem'] = "Conta criada com sucesso. Faça o login.";
                header("Location: ../cidadao/login.php");
                exit();
            } else {
                $erroMensagem = "Erro ao criar conta.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | MuseiwaProg</title>
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            color: #fff;
            background-color: #48ed;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            width: 430px;
            max-width: 98vw;
            min-height: 340px;
            padding: 30px 20px 20px 20px;
            margin: 30px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #fff;
        }

        .container h1 {
            margin-bottom: 10px;
            font-size: 2em;
            letter-spacing: 1px;
            color: #fff;
            text-shadow: 0 2px 8px #0008;
        }

        .container button {
            background-color: rgb(55, 142, 65);
            color: #fff;
            font-size: 17px;
            padding: 12px;
            width: 100%;
            border: 2px solid #fff;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-top: 18px;
            cursor: pointer;
            box-shadow: 0 2px 8px #0002;
            transition: background 0.4s, transform 0.2s;
        }

        .container button:hover {
            background: rgba(11, 2, 53, 0.718);
            color: #fff;
            transform: scale(1.03);
        }

        .container form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container input {
            background-color: #eee;
            margin: 10px 0;
            padding: 10px 15px;
            font-size: 15px;
            border-radius: 8px;
            border: 3px solid #1e00ff;
            width: 100%;
            outline: none;
            color: #222;
            transition: border 0.3s;
        }
        .container input:focus {
            border: 3px solid #378e41;
        }

        .container p {
            margin-top: 18px;
            font-size: 15px;
            color: #fff;
            text-align: center;
        }
        .container a {
            color: #378e41;
            text-decoration: underline;
            font-weight: 600;
        }
        .container a:hover {
            color: #1e00ff;
        }

        .error-message {
            background-color: #ffdddd;
            color: red;
            border: 2px solid red;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            animation: fadeIn 0.5s ease-in-out;
            width: 100%;
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
            .container h1 {
                font-size: 1.3em;
            }
            .container input, .container button {
                font-size: 17px !important;
                width: 100% !important;
            }
            .error-message {
                font-size: 14px !important;
                padding: 8px !important;
            }
        }

        .input-icon {
            position: relative;
            width: 100%;
            margin: 10px 0;
        }

        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #1e00ff;
        }

        .input-icon input {
            padding-left: 30px;
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form action="" method="POST">
                <h1>Criar conta</h1>
                <?php if (!empty($erroMensagem)): ?>
                    <div class="error-message"><?php echo $erroMensagem; ?></div>
                <?php endif; ?>
                <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nome_cidadao" placeholder="Digite o seu nome e apelido" required>
                </div>
                <div class="input-icon">
                    <i class="fa-solid fa-phone"></i>
                    <input type="number" name="contacto_cidadao" placeholder="Digite o contacto" required>
                </div>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="senha" placeholder="Digite a senha" required>
                </div>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirma_senha" placeholder="Confirme a senha" required>
                </div>
                <button type="submit">Registar</button>
                <p>Já tens conta? <a href="login.php">Entrar</a></p>
            </form>
        </div>
    </div>
</body>
</html>
