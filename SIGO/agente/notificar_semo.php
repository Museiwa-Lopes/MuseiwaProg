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
?>

<!DOCTYPE html>
<html>
<head>
  <title>Notificar ocorrência não registada - PRM | MuseiwaProg</title>
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
          <li><a class="button-green" href="/agente/listar_chefes.php">Listar chefes do bairro</a></li>
          <li><a class="button-green" href="/agente/listar_agentes.php">Listar agentes</a></li>
        </ul>
      </div>

      <div class="main-form">
          <form action="notificar_semo.php" method="post">
              <div class="notification-form">
                  <h3>Notificação de ocorrência</h3>

                  <?php
                  $timezone = new DateTimeZone('Africa/Harare'); // Fuso horário de Harare
                  $data_actual = (new DateTime())->setTimezone($timezone)->format('d/m/Y');
                  $hora_actual = (new DateTime())->setTimezone($timezone)->format('H:i');


                  echo'
                  <textarea name="mensagem" id="mensagem" cols="30" rows="10">Nos termos do Artigo 122° do Código Processual Penal vigente no país, é devidamente notificado/a, o/a, nacional que responde pelo nome de _______, residente no bairro _______, área do Distrito de Vilankulo, para comparecer neste comando pelas _:_ minutos, do dia _/'.$data_actual = (new DateTime())->setTimezone($timezone)->format('m/Y').', a fim de prestar declarações sobre factos que lhe são imputados.
NB: A desobediência é punível nos termos da lei.

                                              Vilankulo aos, '.$data_actual.'
                                O oficial de permanência: '.$user->nome.' '.$user->apelido.'
                  </textarea>
                  '?>

                <div class="cont-vis">
                  <label>Contacto do visado:</label>
                    <input type="number" id="contacto_visado" name="contacto_visado" placeholder="Contacto do visado">
                </div>

                <div class="bairro">
                  <label for="bairro">Bairro:</label>
                    <select id="bairro" name="bairro" required onchange="preencherChefeBairro()">
                      <option disabled selected hidden>Seleccione o bairro:</option>
                      <?php
                      include '../includes/conexao.php';

                      $sql = "SELECT * FROM tbl_chefe_bairro";
                      $stmt = $conn->query($sql);

                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option data-contato="' . $row['contacto'] . '">' . $row['bairro'] . '</option>';
                      }

                      $conn = null;
                      ?>
                    </select>

                    <input type="number" id="contacto_chefe_bairro" name="contacto_chefe_bairro" maxlength="9" required placeholder="Contacto do chefe do bairro" readonly>

                </div>



            </div>

                <div class="btn-div">
                  <br><br><button class="submit-button" type="submit">Notificar</button>
                </div>

              </form>
            </div>
    </div>
  </div>

  <script>
    function preencherChefeBairro() {
      var select = document.getElementById('bairro');
      var contactoChefeBairroInput = document.getElementById('contacto_chefe_bairro');

      var selectedOption = select.options[select.selectedIndex];
      var contactoChefeBairro = selectedOption.getAttribute('data-contato');

      contactoChefeBairroInput.value = contactoChefeBairro;
    }
  </script>

  <script>
    // JavaScript para mostrar/ocultar o menu suspenso
    document.querySelectorAll('.sidebar #button-green').forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        var dropdown = this.parentElement.querySelector('.dropdown-menu');
        if (dropdown.style.display === 'block') {
          dropdown.style.display = 'none';
        } else {
          dropdown.style.display = 'block';
        }
      });
    });
  </script>

</body>
</html>

<style>
  *{
    margin: 1;
    padding: 1;
  }
  body {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  background-image: url(/image/PRM.jpeg);
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
  text-decoration: none;
  }

  h3{
  color: white;
  text-align: center;
  }

  .main-form h3 {
  margin-bottom: 5px;
  padding-top: -20px;
  color: #ffffff;
  font-size: 28px;
  text-align: center;
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
  flex: 1; /* Ocupa todo o espaço restante na largura */
  width: 59vw;
  height: 100%;
  padding: 15px;
  display: flex;
  flex-direction: column;
  align-items: center; /* Centralizado horizontalmente */
  justify-content: flex-start;
  font-size: 22px; /* Tamanho da fonte aumentado */
  color: #ffffff;
  text-align: justify; /* Centralizado horizontalmente */
  border-radius: 5px;
  background-color: #ffffffce;
  background-color: #48ed;
  box-shadow: 0 0 10px rgb(59, 58, 58);
  line-height: 1.5;
  }

  .main-form input, select{
  width: 30%;
  padding: 10px;
  margin-bottom: 5px;
  border: 3px solid #1e00ff;
  color: #000000;
  background-color: rgb(255, 255, 255);
  border-radius: 8px;
  box-sizing: border-box;
  text-align: center;
  font-family: 'Times New Roman', Times, serif;
  outline: none;
  }

  #nome_oficial {
  display: flex;
  width: 37%;
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

  .bairro{
    display: flex;
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
  border: 2px solid rgb(255, 255, 255);
  }

  .submit-button {
    background-color: #ff0000;
    color: white;
    border-radius: none;
    display: block;
    padding: 10px;
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    border: 2px solid rgb(255, 255, 255);
    border-radius: 8px;
    text-align: center;
    margin-top: -1.1vh;
    margin-left: 37vh;
    width: 100px;
    font-weight: 600;
  }

  .submit-button:hover{
  cursor: pointer;
  background-color: rgba(255, 0, 30, 0.718);
  border: 2px solid rgb(255, 255, 255);
  cursor: pointer;
  letter-spacing: 0.3px;
  transition: 1s;
  border-radius: 8px;
  color: white;
  transition: 1s;
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

  #mensagem{
    width: 90%;
    height: 90%;
    min-height: 304px;
    max-height: 304px;
    max-width: 700px;
    min-width: 700px;
    border: none;
    color: #ffffff;
    background-color: transparent;
    box-sizing: border-box;
    font-size: 20px;
    text-align: justify;
    line-height: 30px;
    font-family: Georgia, 'Times New Roman', Times, serif ;
    outline: none;
  }
</style>
