<?php
session_start();
session_regenerate_id(true);

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
if ($stmt->rowCount() === 0) {
  header("Location: ../logout.php");
  exit();
}
$user = $stmt->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Gerenciar chefes do bairro - PRM | MuseiwaProg</title>
  <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function pesquisarChefes() {
      const query = $('#search_query').val();
      $.ajax({
        url: 'buscar_chefe.php',
        type: 'POST',
        data: { search_query: query },
        beforeSend: function () {
          $('#resultado-chefes').html('<p>A carregar...</p>');
        },
        success: function (response) {
          $('#resultado-chefes').html(response);
        },
        error: function () {
          $('#resultado-chefes').html('<p>Erro ao carregar chefes.</p>');
        }
      });
    }

    $(document).ready(function () {
      pesquisarChefes();
    });

  </script>
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

      <div class="cheads-list">
        <h3>Lista dos chefes do bairro</h3>
        <div class="add-button-container">
          <form class="search-form" onsubmit="return false;">
            <input type="text" id="search_query" placeholder="Buscar..." oninput="pesquisarChefes()">
          </form>
        </div>

        <div id="resultado-chefes"></div>

      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.sidebar #button-green').forEach(function (button) {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        var dropdown = this.parentElement.querySelector('.dropdown-menu');
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
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
  text-decoration: none;
  }

  #resultado-chefes{
  width: 100%;
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

  .cheads-list h2 {
  margin-bottom: 20px;
  color: #ddd;
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
  .cheads-list{
  width: 59vw;
  height: 100%;
  padding: 15px;
  background-color: #48ed;
  box-shadow: 0 0 10px rgb(59, 58, 58);
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  overflow-y: auto;
}

  .cheads-item {
  background-color: #ffffff;
  border-radius: 8px;
  padding: 10px;
  margin: 10px 0;
  }
  h3, tr{
  color: white;
  }

  .cheads-item h4 {
  margin: 0;
  }

  .cheads-item p {
    margin: 5px 0;
  }


  .cheads-table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #ccc;
  margin-top: 20px;
  box-shadow: 0 0 10px rgb(59, 58, 58);
  }

  .cheads-table th,
  .cheads-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
  }

  .cheads-table th {
  background-color: rgb(55, 142, 65);
  }
  .search-form input {
  width: 115px;
  padding: 5px 8px;
  font-size: 16px;
  border: 2px solid #ddd;
  border-radius: 20px;
  outline: none;
  transition: all 0.3s ease-in-out;
  font-family: 'Times New Roman', Times, serif;
}

.search-form input:focus {
  border-color: rgb(55, 142, 65);
  box-shadow: 0 0 8px rgba(55, 142, 65, 0.5);
}
.delete-button,
  .add-button{
    padding: 5px 8px;
    margin: 0;
    border: 2px solid rgb(255, 255, 255);
    background-color: #ff0000;
    color: white;
    cursor: pointer;
    border-radius: 8px;
    font-size: 16px;
    font-family: 'Times New Roman', Times, serif;
    margin-left: 5%;
    font-weight: 600;
  }

  .update-button{
    padding: 5px 8px;
    margin-top: 2px;
    border: 2px solid rgb(255, 255, 255);
    background-color: #0400ff;
    color: white;
    cursor: pointer;
    border-radius: 8px;
    font-size: 16px;
    font-family: 'Times New Roman', Times, serif;
    font-weight: 600;
  }

  .update-button:hover{
    background-color: rgba(25, 73, 231, 0.718);
    transition: 0.5s;
  }

  .delete-button:hover{
    background-color: rgba(255, 0, 30, 0.718);
    transition: 0.5s;
  }

  .add-button {
    background-color:  rgb(55, 142, 65);
    color: white;
    border-radius: none;
    display: block;
    width: 120px;
    margin-top: 5px;
    margin-bottom: 12px;
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    border: 2px solid rgb(255, 255, 255);
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
  }

  a{
    text-decoration: none;
    color: white;
  }
  .add-button:hover {
    background-color: rgba(11, 2, 53, 0.718);
    border: 2px solid rgb(255, 255, 255);
    cursor: pointer;
    transition: 1s;
    border-radius: 8px;
    color: white;
    border: 2px solid rgb(255, 255, 255);
    text-align: center;
  }
  .add-button-container {
    margin-top: 20px;
    margin-left: 70%;
  }
  td{
    color: black;
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
  border-radius: 5px;
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
