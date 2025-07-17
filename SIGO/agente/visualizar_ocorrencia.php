<?php
session_start();
session_regenerate_id(true); // Regenera o ID da sessão para maior segurança

// Verifica se o usuário está logado e é Agente
if (!isset($_SESSION["user"]) || $_SESSION["tipo_usuario"] !== "Agente") {
    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Erro de Acesso | PRM Vilankulo</title>
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
            .error-container {
                max-width: 400px;
                margin: 50px auto;
                padding: 28px 18px 18px 18px;
                background: #48ed;
                border: 3px solid rgb(255, 0, 0);
                border-radius: 30px;
                text-align: center;
                box-shadow: 0 0 10px rgb(59, 58, 58);
            }
            .error-container h2 {
                color: rgb(255, 0, 0);
                margin-bottom: 12px;
                font-size: 1.5em;
            }
            .error-container p {
                color: #fff;
                margin-bottom: 18px;
                font-size: 1.1em;
            }
            .error-button {
                display: inline-block;
                padding: 10px 22px;
                color: white;
                background-color: rgb(55, 142, 65);
                border: 2px solid #fff;
                text-decoration: none;
                border-radius: 8px;
                font-weight: bold;
                font-size: 1em;
                transition: background 0.4s, transform 0.2s;
            }
            .error-button:hover {
                background-color: rgba(11, 2, 53, 0.718);
                color: #fff;
                transform: scale(1.04);
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
                .error-container {
                    width: 98vw !important;
                    min-width: unset !important;
                    max-width: 99vw !important;
                    padding: 10px 2vw !important;
                    border-radius: 15px !important;
                    margin: 10px auto !important;
                }
                .error-container h2 {
                    font-size: 1.1em;
                }
                .error-container p, .error-button {
                    font-size: 1em !important;
                }
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h2>Erro de Acesso!</h2>
            <p>Acesso negado! Faça login para continuar.</p>
            <a href="../logout.php" class="error-button">Fazer Login!</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

include '../includes/conexao.php';

$distintivo = $_SESSION["distintivo"];
$stmt = $conn->prepare("SELECT * FROM tbl_usuarios WHERE distintivo = :nrdistintivo");
$stmt->bindParam(":nrdistintivo", $distintivo);
$stmt->execute();

// Se não encontrar o usuário, redireciona para o login
if ($stmt->rowCount() === 0) {
    header("Location: ../logout.php");
    exit();
}

$user = $stmt->fetch(PDO::FETCH_OBJ);

// Verifica se foi passado um ID da ocorrência via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo'
    <div class="error-container">
    <h3>Erro: Ocorrência não especificada!</h3>
    </div>
    <style>
    body {
        margin: 0;
        padding: 0;
        background-image: url(/image/PRM.jpeg);
        background-repeat: round;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        height: 84vh;
        font-family: Cambria, serif;
        overflow: hidden;
    }
    .error-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        background: #48ed;
        border: 3px solid rgb(255, 0, 0);
        border-radius: 8px;
        text-align: center;
    }
    .error-container h2 {
        color: rgb(255, 0, 0);
    }
    .error-button {
        display: inline-block;
        padding: 10px 15px;
        color: white;
        background-color: rgb(55, 142, 65);
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
    }
  </style>';
    exit();
}

$id_ocorrencia = $_GET['id'];

// Busca a ocorrência no banco de dados
$stmt = $conn->prepare("SELECT * FROM tbl_ocorrencia WHERE id = :id");
$stmt->bindParam(":id", $id_ocorrencia, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo "<p>Ocorrência não encontrada.</p>";
    exit();
}

$ocorrencia = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registar ocorrência - PRM | MuseiwaProg</title>
  <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
</head>
<body>
  <div class="container">
        <header class="header">
            <h2>PRM Vilankulo [Agente]</h2>
            <div class="profile-options">
                <a href="#">
                    <?php echo htmlspecialchars($user->nome) . ' ' . htmlspecialchars($user->apelido) . ' | Distintivo: ' . htmlspecialchars($user->distintivo); ?>
                </a>
                <a href="inicio.php" id="inicio">Página inicial</a>
                <a href="../logout.php" id="sair">Sair</a>
            </div>
        </header>

    <div class="content">

    <div class="sidebar">
        <ul>
          <li>
            <a id="button-green" class="button-green" href="#">Ocorrência</a>
            <ul class="dropdown-menu">
              <li><a href="/agente/registar_ocorrencia.php">Registar ocorrência</a></li>
              <li><a href="/agente/listar_ocorrencias.php">Listar ocorrências</a></li>
              <li><a href="/agente/notificar_semo.php">Notificar ocorrência</a></li>
            </ul>
          </li>
          <li><a class="button-green" href="/agente/listar_chefes.php">Gerenciar chefes do bairro</a></li>
          <li><a class="button-green" href="/agente/listar_agentes.php">Listar agentes</a></li>
        </ul>
      </div>

<div class="main-form">

        <div class="div-1">
          <form action="" method="POST">

          <h3>(Classificação do caso)</h3>

          <div class="container-1">
              <div class="d1">
                <label for="nome_cidadao">Nome do cidadão:</label>
                <input type="text" id="nome_cidadao" name="nome_cidadao" value="<?= $ocorrencia['nome_cidadao'] ?>" readonly>
              </div>

              <div class="d2">
              <label for="sexo_cidadão">Sexo do cidadão:</label>
              <input name="sexo_cidadao" id="sexo_cidadao" value="<?= $ocorrencia['sexo_cidadao'] ?>" readonly>
              </div>
          </div>

          <div class="container-2">
            <div class="d3">
              <label for="estado_civil">Estado civil do cidadão:</label>
              <input name="estado_civil" id="estado_civil" value="<?= $ocorrencia['estado_civil'] ?>" readonly>
            </div>

            <div class="d4">
              <label for="idade_cidadao">Idade do cidadão:</label>
              <input type="number" id="idade_cidadao" name="idade_cidadao" value="<?= $ocorrencia['idade_cidadao'] ?>" readonly>
            </div>
          </div>

          <div class="container-3">
            <div class="d5">
              <label for="mae">Mãe do cidadão:</label>
              <input type="text" id="mae_cidadao" name="mae_cidadao" value="<?= $ocorrencia['mae_cidadao'] ?>" readonly>
            </div>

            <div class="d6">
              <label for="pai_cidadao">Pai do cidadão:</label>
              <input type="text" id="pai_cidadao" name="pai_cidadao" value="<?= $ocorrencia['pai_cidadao'] ?>" readonly>
            </div>
          </div>

          <div class="container-4">
            <div class="d7">
              <label for="data_nascimento">Data de nascimento:</label>
              <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $ocorrencia['data_nascimento'] ?>" readonly>
            </div>

            <div class="d8">
              <label for="naturalidade_distrito">Naturalidade (Distrito):</label>
              <input type="text" id="naturalidade_distrito" name="naturalidade_distrito" value="<?= $ocorrencia['naturalidade_distrito'] ?>" readonly>
            </div>

          </div>

          <div class="container-5">
            <div class="d9">
              <label for="provincia">Província:</label>
              <input name="provincia" id="provincia" value="<?= $ocorrencia['provincia'] ?>" readonly>
            </div>

            <div class="d10">
              <label for="nacionalidade">Nacionalidade:</label>
              <input name="nacionalidade" id="nacionalidade" value="<?= $ocorrencia['nacionalidade'] ?>" readonly>
            </div>
          </div>

            <div class="container-6">
              <div class="d11">
                <label for="local_trabalho">Endereço do trabalho:</label>
                <input type="text" id="local_trabalho" name="local_trabalho" value="<?= $ocorrencia['local_trabalho'] ?>" readonly>
              </div>

              <div class="d12">
                <label for="bairro">Residente do bairro:</label>
                  <input id="bairro" name="bairro" value="<?= $ocorrencia['bairro'] ?>" readonly>
              </div>

                <div class="">
                  <label for="chefe_bairro" hidden>Chefe do bairro:</label>
                  <input type="text" id="chefe_bairro" name="chefe_bairro" maxlength="9" required placeholder="Nome do chefe do bairro" hidden>
                </div>

                <div class="">
                  <label for="contacto_chefe_bairro" hidden>Contacto do chefe do bairro:</label>
                  <input type="number" id="contacto_chefe_bairro" name="contacto_chefe_bairro" maxlength="9" required placeholder="Contacto do chefe do bairro" hidden>
                </div>
              </div>

          <div class="container-7">
              <div class="d13">
                <label for="contacto_cidadao">Contacto do cidadão:</label>
                <input type="number" id="contacto_cidadao" name="contacto_cidadao" value="<?= $ocorrencia['contacto_cidadao'] ?>" readonly>
              </div>

              <div class="d14">
              <label for="classificacao">Classificação:</label>
              <input id="classificacao" name="classificacao" value="<?= $ocorrencia['classificacao'] ?>" readonly>
            </div>
          </div>
        </div>

        <div class="div-2">

          <h3>(Características do caso)</h3>

          <div class="container-8">
            <div class="d15">
              <label for="data_ocorrido">Data do ocorrido:</label>
              <input type="date" id="data_ocorrido" name="data_ocorrido" value="<?= $ocorrencia['data_ocorrido'] ?>" readonly>
            </div>

            <div class="d16">
              <label for="hora">Hora do ocorrido</label>
              <input type="time" id="hora_ocorrido" name="hora_ocorrido" value="<?= $ocorrencia['hora_ocorrido'] ?>" readonly>
            </div>
          </div>

          <div class="container-9">
            <div class="d17">
              <label for="lugar_ocorrido">Lugar do ocorrido:</label>
              <input type="text" id="lugar_ocorrido" name="lugar_ocorrido" value="<?= $ocorrencia['lugar_ocorrido'] ?>" readonly>
            </div>

            <div class="d18">
              <label for="endereco_caso">Endereço do caso:</label>
              <input type="text" id="endereco_caso" name="endereco_caso" value="<?= $ocorrencia['endereco_caso'] ?>" readonly>
            </div>
          </div>

          <div class="container-10">
            <div class="d19">
              <label for="visado">Nome do visado:</label>
              <input type="text" id="nome_visado" name="nome_visado" value="<?= $ocorrencia['nome_visado'] ?>" readonly>
            </div>

            <div class="d20">
              <label for="contacto_visado">Contacto do visado:</label>
              <input type="number" id="contacto_visado" name="contacto_visado" value="<?= $ocorrencia['contacto_visado'] ?>" readonly>
            </div>
          </div>

          <div class="21">
            <label for="descricao">Descrição do ocorrido:</label>
            <textarea id="descricao_ocorrido" name="descricao_ocorrido" rows="4" readonly><?= $ocorrencia['descricao_ocorrido'] ?></textarea>
          </div>

          <!-- <div class="datetime-container">

                  <?php
                  $timezone = new DateTimeZone('Africa/Harare'); // Fuso horário de Harare
                  $data_actual = (new DateTime())->setTimezone($timezone)->format('Y-m-d');
                  $hora_actual = (new DateTime())->setTimezone($timezone)->format('H:i');
                  ?>

                <div class="d19">
                  <label for="data">Data:</label>
                  <input class="inp" value="<?= $data_actual; ?>" type="date" id="data" name="data" required>
                </div>

                <div class="d20">
                  <label for="hora">Hora:</label>
                  <input type="time" value="<?= $hora_actual; ?>" id="hora" name="hora" required>
                </div>

              </div> -->

            <div class="container-11">
              <div class="d23">
                  <a href="listar_ocorrencias.php" class="close-button">Voltar</a>
              </div>

              <div class="d22">
                <a href="gerar_autopdf.php?id=<?= $_GET['id'] ?>" target="_blank">
                  <button type="button" class="print-button">Imprimir</button>
                </a>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>

  </div>

</body>
</html>

<style>

  *{
    margin: 1;
    padding: 1;
  }

  body{
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

  .header {
    background-color: #48ed;
    box-shadow: 0 0 10px rgb(59, 58, 58);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 5px;
    margin-top: 30px;
  }

  .profile-options a {
    color: white;
    margin-right: 65px;
    text-decoration: none;
  }

  h2{
  color: white;
  margin-left: 2%;
  }

  h3{
    color: white;
    margin-top: 0;
    margin-bottom: 0;
    margin-left: 25%;
  }

  .content {
    width: 100%;
    height: 80vh;
    display: flex;
  }

  .sidebar {
    width: 250px;
    height: 80vh;
    background-color: #333;
    box-shadow: 0 0 10px rgb(59, 58, 58);
    color: white;
    padding: 15px;
    border-radius: 5px;
  }

  .sidebar ul {
    list-style: none;
    padding: 8px;
  }

  .sidebar li {
    margin-bottom: 15px;
  }

  .main-form {
    width: 59vw;
    height: 100%;
    padding: 15px;
    background-color: #ffffffce;
    background-color: #48ed;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 10px rgb(59, 58, 58);
  }

  .div-1{
   width: 100%;
   height: 100%;
  }

  .div-2{
    width: 100%;
    height: 100%;
   }

  .main-form  label {
    color: #ffffff;
    font-size: 20px;
    font-family: 'Times New Roman', Times, serif;
  }

  .main-form select{
    padding: 10px;
    border: 3px solid #1e00ff;
    border-radius: 8px;
    box-sizing: border-box;
  }

  .main-form input[type="date"] {
    width: 100%;
    padding: 10px;
    border: 3px solid #1e00ff;
    border-radius: 8px;
    box-sizing: border-box;
    color: #000000;
    background-color: #ffffff;
    font-family: 'Times New Roman', Times, serif;
    text-align: center;
  }

  .main-form input[type="time"]{
    width: 100%;
    padding: 10px;
    border: 3px solid #1e00ff;
    border-radius: 8px;
    color: #000000;
    background-color: rgb(255, 255, 255);
    box-sizing: border-box;
    font-family: 'Times New Roman', Times, serif;
    text-align: center;
  }

  .d1{
    width: 50%;
  }

  .d2{
    width: 50%;
  }

  .d3{
    width: 50%;
  }

  .d4{
    width: 50%;
  }

  .d5{
    width: 50%;
  }

  .d6{
    width: 50%;
  }

  .d7{
    width: 50%;
  }

  .d8{
    width: 50%;
  }

  .d9{
    width: 50%;
  }

  .d10{
    width: 50%;
  }

  .d11{
    width: 50%;
  }

  .d12{
    width: 50%;
  }

  .d13{
    width: 50%;
  }

  .d14{
    width: 50%;
  }

  .d15{
    width: 50%;
  }

  .d16{
    width: 50%;
  }

  .d19{
    width: 100%;
  }

  .d20{
    width: 100%;
  }

  .d21{
    width: 100%;
  }

  .d22{
    width: 50%;
  }

  .d23{
    width: 50%;
  }

  .datetime-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }

  .container-1{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-2{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-3{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-4{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-5{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-6{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-7{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-8{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-9{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-10{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }
  .container-11{
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 95%;
    justify-content: space-between;
  }

  .main-form input {
    max-width: 90%;
    min-width: 90%;
    padding: 12px;
    margin-bottom: 15px;
    border: 3px solid #1e00ff;
    outline: none;
    color: #000000;
    background-color: rgb(255, 255, 255);
    border-radius: 8px;
    box-sizing: border-box;
    font-family: 'Times New Roman', Times, serif;
    text-align: center;
  }

  .main-form select{
    max-width: 90%;
    min-width: 90%;
    padding: 11.5px;
    margin-bottom: 15px;
    color: #000000;
    background-color: rgb(255, 255, 255);
    border: 3px solid #1e00ff;
    outline: none;
    border-radius: 8px;
    box-sizing: border-box;
    font-family: 'Times New Roman', Times, serif;
    text-align: center
  }

  .main-form textarea {
    width: 90%; /* Usa todo o espaço disponível */
    max-width: 90%; /* Evita que ultrapasse o contêiner */
    height: 210px; /* Altura fixa para garantir visibilidade */
    max-height: 210px; /* Define um limite para altura */
    margin-bottom: 11px;
    padding: 10px;
    border: 3px solid #1e00ff;
    outline: none;
    border-radius: 8px;
    color: #4d4c4c;
    background-color: #fff;
    box-sizing: border-box;
    resize: vertical;
    font-family: 'Times New Roman', Times, serif;
    text-align: center;
  }

  #inicio {
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    background-color: rgb(55, 142, 65);
    border: 2px solid rgb(255, 255, 255);
    border-radius: 20px;
    padding: 5px 10px;
    font-weight: 600;
  }

  #inicio:hover{
    background-color: rgba(11, 2, 53, 0.718);
    transition: 1s;
  }

  #sair{
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    background-color: rgba(255, 0, 30, 0.72);
    border: 2px solid rgb(255, 255, 255);
    border-radius: 20px;
    padding: 5px 15px;
    font-weight: 600;
  }

  #sair:hover{
    background-color: #ff0000;
    transition: 1s;
  }

  .button-green,
  .button-blue {
    display: block;
    padding: 10px 16px;
    margin-bottom: 5px;
    text-align: center;
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    font-weight: 600;
  }

  .button-green {
    background-color: rgb(55, 142, 65);
    border: 2px solid rgb(255, 255, 255);
  }

  .button-green:hover {
    background-color: rgba(11, 2, 53, 0.718);
    border: 2px solid rgb(255, 255, 255);
    cursor: pointer;
    letter-spacing: 0.3px;
    transition: 1s;
    border-radius: 8px;
    color: white;
    transition: 1s;
  }

  .print-button {
    background-color: rgb(55, 142, 65);
    color: white;
    border-radius: none;
    display: block;
    padding: 10px;
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    border: 2px solid rgb(255, 255, 255);
    border-radius: 8px;
    text-align: center;
    width: 90%;
    margin-top: 11.5%;
    font-weight: 600;
    text-decoration: none;
    }
    a {
      text-decoration: none;
    }

  .print-button:hover{
    cursor: pointer;
    background-color: rgba(0, 0, 0, 0.718);
    border: 2px solid rgb(255, 255, 255);
    cursor: pointer;
    letter-spacing: 0.3px;
    transition: 1s;
    border-radius: 8px;
    color: white;
    transition: 1s;
    border: 2px solid rgb(255, 255, 255);
  }

  .close-button {
    background-color: rgb(55, 142, 65);
    color: white;
    border-radius: none;
    display: block;
    padding: 10px;
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    border: 2px solid rgb(255, 255, 255);
    border-radius: 8px;
    text-align: center;
    width: 80%;
    margin-top: 11.5%;
    font-weight: 600;
    text-decoration: none;
    }

  .close-button:hover{
    cursor: pointer;
    background-color: rgba(0, 0, 0, 0.718);
    border: 2px solid rgb(255, 255, 255);
    cursor: pointer;
    letter-spacing: 0.3px;
    transition: 1s;
    border-radius: 8px;
    color: white;
    transition: 1s;
    border: 2px solid rgb(255, 255, 255);
  }

  /* Esconder o menu suspenso por padrão */
  .sidebar .dropdown-menu {
    display: none;
  }

  /* Estilize o link pai para indicar que é um menu suspenso */
  .sidebar li:hover .button-green {
    background-color: rgba(11, 2, 53, 0.718);
    border-radius: 8px;
  }

  /* Mostrar o menu suspenso quando o item pai estiver em foco */
  .sidebar li:hover .dropdown-menu {
    display: block;
    position: absolute;
    background-color: #333;
    margin-top: -10px; /* Ajuste a posição do menu suspenso conforme necessário */
    border-radius: 8px;
    box-shadow: 0 0 10px rgb(0, 0, 0);
  }

  /* Estilize os itens do menu suspenso */
  .sidebar .dropdown-menu li {
    margin-bottom: 0; /* Remova o espaço entre os itens do menu */
  }

  /* Estilize os links do menu suspenso */
  .sidebar .dropdown-menu a {
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: block;
    color: white;
    border-radius: 8px;
    font-weight: 600;
  }

  /* Altere a cor do link quando passar o mouse sobre os itens do menu suspenso */
  .sidebar .dropdown-menu a:hover {
    background-color: rgba(11, 2, 53, 0.718);
    border-radius: 8px;
  }

</style>
