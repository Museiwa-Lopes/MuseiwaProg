<?php
include "includes/conexao.php";
session_start();

$erroMensagem = ""; // Inicializa a variável de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nrdistintivo = $_POST["nrdistintivo"];
    $senha = $_POST["password"];

    $query = "SELECT * FROM tbl_usuarios WHERE distintivo = :nrdistintivo";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":nrdistintivo", $nrdistintivo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $senhaCriptografadaNoBanco = $row["senha"];

        if (password_verify($senha, $senhaCriptografadaNoBanco)) {
            $_SESSION["user"] = true;

            if ($row["tipo_usuario"] == "Admin_Master") {
                $_SESSION["tipo_usuario"] = "Admin_Master";
                $_SESSION["distintivo"]   = $nrdistintivo;
                $_SESSION["id_usuario"]   = $row["id"];
                header("Location: ../admin/inicio.php");
                exit;
            } elseif ($row["tipo_usuario"] == "Admin") {
                $_SESSION["tipo_usuario"] = "Admin";
                $_SESSION["distintivo"]   = $nrdistintivo;
                $_SESSION["id_usuario"]   = $row["id"];
                header("Location: ../admin/inicio.php");
                exit;
            } elseif ($row["tipo_usuario"] == "Agente") {
                $_SESSION["tipo_usuario"] = "Agente";
                $_SESSION["distintivo"]   = $nrdistintivo;
                $_SESSION["id_usuario"]   = $row["id"];
                header("Location: ../agente/inicio.php");
                exit;
            }
        } else {
            $erroMensagem = "Senha incorrecta!";
        }
    } else {
        $erroMensagem = "Distintivo inválido!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PRM | MuseiwaProg</title>
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            min-height: 100vh;
            width: 100vw;
            background-image: url(/image/PRM.jpeg);
            background-repeat: round;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(10, 20, 40, 0.45);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            color: #fff;
            background: #48ed;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            width: 400px;
            max-width: 98vw;
            min-height: 320px;
            padding: 36px 28px 28px 28px;
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

        .container span {
            font-size: 13px;
            color: #378e41;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .container button {
            background: rgb(55, 142, 65);
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
            background: #fff;
            border: 3px solid #1e00ff;
            margin: 10px 0;
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 8px;
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
            color: rgb(255, 0, 0);
            border: 2px solid rgb(255, 0, 0);
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-family: Georgia, 'Times New Roman', Times, serif;
            box-shadow: 0px 0px 5px rgba(255, 0, 12, 0.5);
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
            font-size: 18px;
        }

        .input-icon input {
            padding-left: 30px;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form action="" method="post">
                <h1>Admin | Agente</h1>
                <span>ACESSO AUTORIZADO</span>

                <?php if (!empty($erroMensagem)): ?>
                    <div class="error-message"><?php echo $erroMensagem; ?></div>
                <?php endif; ?>

                <div class="input-icon">
                    <i class="fa-solid fa-id-badge"></i>
                    <input required placeholder="N° do distintivo" type="text" name="nrdistintivo" id="nrdistintivo" maxlength="6">
                </div>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input required placeholder="Senha" type="password" name="password" id="password" maxlength="10">
                </div>
                <button type="submit" id="submeter" value="">Acesso Restrito</button>
            </form>
        </div>
    </div>
</body>
</html>
