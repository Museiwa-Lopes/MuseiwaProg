<?php
include '../includes/conexao.php';

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full-name"];
    $gender = $_POST["gender"];
    $contact = $_POST["contact"];
    $neighborhood = $_POST["neighborhood"];

    try {
        $sql = "INSERT INTO tbl_chefe_bairro (nome_completo, genero, contacto, bairro) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $gender, $contact, $neighborhood]);
        $success_message = '<div class="success-message">Cadastro realizado com sucesso!</div>';
        header("location: gerenciar_chefes.php");
        exit();
    } catch (PDOException $e) {
        $error_message = '<div class="error-message">Erro ao Cadastrar: ' . $e->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Adicionar chefe - PRM | MuseiwaProg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
  <style>
    *{
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
    .container:hover{
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
      background-color:  rgb(55, 142, 65);
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
    .container a{
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
    .container a:hover{
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
    <h3>Adicionar chefe do bairro</h3>
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
          <label for="full-name">Nome completo:</label>
          <input type="text" id="full-name" name="full-name" required placeholder="Digite o nome completo">
        </div>
        <div>
          <label for="bairro">Bairro:</label>
          <input type="text" id="neighborhood" name="neighborhood" required placeholder="Digite o bairro">
        </div>
      </div>
      <div>
        <label for="gender">Género do chefe de Bairro:</label>
        <select class="gender-select" id="gender" name="gender" required>
          <option disabled selected hidden>Seleccione o género</option>
          <option value="Masculino">Masculino</option>
          <option value="Feminino">Feminino</option>
        </select>
        <label for="contact">Contacto do chefe do bairro:</label>
        <input type="text" id="contact"  name="contact" maxlength="9" required placeholder="Digite o contacto">
      </div>
      <button class="submit-button" type="submit">Cadastrar</button>
      <a href="gerenciar_chefes.php">⬅️ Sair</a>
    </form>
  </div>
</body>
</html>
