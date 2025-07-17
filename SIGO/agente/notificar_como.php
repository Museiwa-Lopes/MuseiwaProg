<?php
session_start();
session_regenerate_id(true); // Regenera o ID da sessão para maior segurança

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION["user"]) || $_SESSION["tipo_usuario"] !== "Agente") {
  echo '
  <div class="error-container">
      <h2>Erro de Acesso!</h2>
      <p>Acesso negado! Faça login para continuar.</p>
      <a href="../logout.php" class="error-button">Fazer Login!</a>
  </div>

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
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      overflow: hidden;
}

  .error-container {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      background: #48ed;
      border: 3px solid rgb(255, 0, 0);
      border-radius: 30px;
      text-align: center;
      font-family: "Times New Roman", Times, serif;
  }

  .error-container h2 {
      color: rgb(255, 0, 0);
  }

  .error-button {
      display: inline-block;
      padding: 10px 15px;
      color: white;
      background-color: rgb(55, 142, 65);
      border: 2px solid rgb(255, 255, 255);
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
  }

  .error-button:hover {
      transition: 1s;
      background-color: rgba(11, 2, 53, 0.718);
  }

  </style>';
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
  <title>Notificar - PRM | MuseiwaProg</title>
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
          <li><a class="button-green" href="/agente/gerenciar_chefes.php">Listar chefes do bairro</a></li>
          <li><a class="button-green" href="/agente/gerenciar_agentes.php">Listar agentes</a></li>
        </ul>
      </div>

      <?php
      include '../includes/conexao.php';

      if (isset($_POST['notificar'])){
        $id = $_POST['notificar'];

        $sql = "SELECT * FROM tbl_ocorrencia WHERE `id` = $id";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute()){
          while($res = $stmt->fetch(PDO::FETCH_OBJ)){
            $nome_visado = $res->nome_visado;
            $bairro = $res->bairro;

            $timezone = new DateTimeZone('Africa/Harare'); // Fuso horário de Harare
            $data_actual = (new DateTime())->setTimezone($timezone)->format('d/m/Y');
            $hora_actual = (new DateTime())->setTimezone($timezone)->format('H:i');


            echo'
            <div class="main-form">
              <form action="enviar_notificacao.php" method="post">
                <div class="notification-form">
                  <h3>Notificação de ocorrência</h3>

                  <textarea name="mensagem" id="mensagem" cols="50" rows="20">Nos termos do Artigo 122° do Código Processual Penal vigente no país, é devidamente notificado/a, o/a, nacional que responde pelo nome de '.$nome_visado.', residente no bairro '.$bairro.', área do Distrito de Vilankulo, para comparecer neste comando pelas _:_ minutos, do dia _/'.$data_actual = (new DateTime())->setTimezone($timezone)->format('m/Y').', a fim de prestar declarações sobre factos que lhe são imputados.
NB: A desobediência é punível nos termos da lei.

                                              Vilankulo aos, '.$data_actual.'
                                O oficial de permanência: '.$user->nome.' '.$user->apelido.'
                  </textarea>
                </div>


                <input type="hidden" id="contacto_chefe_bairro"value="'.htmlspecialchars($res->contacto_chefe_bairro).'" name="contacto_chefe_bairro">
                <input type="hidden" id="contacto_visado" value="'.htmlspecialchars($res->contacto_visado).'" name="contacto_visado">

                <div class="btn-div">
                  <button class="submit-button" id="botao-notificar" type="submit" name="notificar">Notificar</button>
                </div>
              </form>
            </div>
            ';
          }
        }
      }
      ?>
                <!-- <label for="nome_oficial" id="nome_oficial">O oficial de permanência</label>
                <input type="text" id="nome_oficial" placeholder="Nome do oficial" value="'.htmlspecialchars($user->nome).' '.htmlspecialchars($user->apelido).'"> -->

    </div>
  </div>

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

  h2 {
  color: white;
  margin-left: 2%;
  text-decoration: none;
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

  .main-form input {
  width: 32%;
  padding: 10px;
  border: 3px solid #1e00ff;
  color: #000000;
  background-color: rgb(255, 255, 255);
  border-radius: 8px;
  box-sizing: border-box;
  font-family: 'Times New Roman', Times, serif;
  text-align: center;
  }

  #data_assinatura {
  align-items: center;
  justify-content: center;
  }

  .main-form h3 {
  margin-bottom: 5px;
  padding-top: -20px;
  color: #ffffff;
  font-size: 28px;
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

  .button-green{
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

  .button-green{
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
    padding: 10px;
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    border: 2px solid rgb(255, 255, 255);
    border-radius: 8px;
    text-align: center;
    margin-top: 19vh;
    margin-left: 37vh;
    width: 100px;
    font-weight: 600;
  }

  .submit-button:hover{
  cursor: pointer;
  background-color: rgba(255, 0, 30, 0.718);
  transition: 0.5s;
  border: 2px solid rgb(255, 255, 255);
  cursor: pointer;
  border-radius: 8px;
  color: white;
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

  #mensagem{
    width: 90%;
    height: 90%;
    min-height:304px;
    max-height:304px;
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
