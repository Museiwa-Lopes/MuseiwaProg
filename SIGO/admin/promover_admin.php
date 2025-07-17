<?php
include '../includes/conexao.php';

$success_message = "";
$error_message = "";
session_start();

  if ($_SESSION["tipo_usuario"] === "Admin" || $_SESSION["tipo_usuario"] === "Admin_Master") {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $name = $_POST["name"];
      $nickname = $_POST["nickname"];
      $gender = $_POST["gender"];
      $badge_number = $_POST["badge-number"];
      $password = $_POST["password"];
      $confirm_password = $_POST["confirm-password"];
      $user_type = "Admin";

      // Verifica se as senhas são diferentes
      if ($password !== $confirm_password) {
          $error_message = '<div class="error-message">As senhas não coincidem! Tente novamente.</div>';
      } else {
          // Verifique se o número de distintivo já existe no banco de dados
          $check_query = "SELECT COUNT(*) FROM tbl_usuarios WHERE distintivo = ?";
          $stmt_check = $conn->prepare($check_query);
          $stmt_check->execute([$badge_number]);
          $badge_exists = $stmt_check->fetchColumn();

          if ($badge_exists > 0) {
              $error_message = '<div class="error-message">O número de distintivo já está em uso! Cadastro não realizado.</div>';
          } else {
              try {
                  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                  $sql = "INSERT INTO tbl_usuarios (nome, apelido, genero, distintivo, senha, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?)";
                  $stmt = $conn->prepare($sql);

                  if ($stmt->execute([$name, $nickname, $gender, $badge_number, $hashed_password, $user_type])) {
                      $success_message = '<div class="success-message">Cadastro realizado com sucesso!</div>';
                      // Redireciona para a página de gerenciamento de agentes após o cadastro
                      header("Location: /admin/gerenciar_admin.php");
                      exit;
                  } else {
                      $error_message = '<div class="error-message">Erro ao cadastrar agente.</div>';
                  }
              } catch (PDOException $e) {
                  $error_message = '<div class="error-message">Erro no banco de dados: ' . $e->getMessage() . '</div>';
              }
          }
      }
  }
} else {
  header("Location: ../logout.php");
}

$conn = null;

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Promover admin - PRM | MuseiwaProg</title>
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
        <h3>Promover administrador</h3>

        <?php
        if ($error_message !== "") {
            echo $error_message;
        }
        if ($success_message !== "") {
            echo $success_message;
        }
        ?>

        <form action="" method="post">
            <div class="form-row">
                <div>
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" required placeholder="Digite o primeiro nome">
                </div>
                <div>
                    <label for="nickname">Apelido:</label>
                    <input type="text" id="nickname" name="nickname" required placeholder="Digite o apelido">
                </div>
            </div>
            <div>
                <label for="gender">Género:</label>
                <select class="gender-select" id="gender" name="gender" required>
                    <option disabled selected hidden>Seleccione o género</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                </select>
            </div>
            <div>
                <label for="badge-number">N° de distintivo:</label>
                <input type="text" id="badge-number" maxlength="6" name="badge-number" required placeholder="N° de distintivo">
            </div>
            <div class="form-row">
                <div>
                    <label for="password">Senha:</label>
                    <input type="password" id="password" maxlength="10" name="password" required placeholder="Digite a senha">
                </div>
                <div>
                    <label for="confirm-password">Confirmação da senha:</label>
                    <input type="password" id="confirm-password" maxlength="10" name="confirm-password" required placeholder="Confirma a senha">
                </div>
            </div>
            <button class="submit-button" type="submit">Promover</button>
            <a href="gerenciar_admin.php">⬅️ Sair</a>
        </form>
    </div>
</body>
</html>
