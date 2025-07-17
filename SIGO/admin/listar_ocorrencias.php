<?php
session_start();
session_regenerate_id(true); // Regenera o ID da sessão para maior segurança

// Verifica se o usuário está logado e é administrador
if (
    !isset($_SESSION["user"]) ||
    ($_SESSION["tipo_usuario"] !== "Admin" && $_SESSION["tipo_usuario"] !== "Admin_Master")
) {
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
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar ocorrências - PRM | MuseiwaProg</title>
    <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
</head>
<body>
    <div class="container">
        <header class="header">
            <h2>PRM Vilankulo [Administrador]</h2>
            <div class="profile-options">
                <a href="#">
                    <?php echo htmlspecialchars($user->nome) . ' ' . htmlspecialchars($user->apelido) . ' | Distintivo: ' . htmlspecialchars($user->distintivo); ?>
                </a>
                <a href="inicio.php" id="inicio">Página inicial</a>
                <a href="../logout.php" id="sair">Sair</a>
            </div>
        </header>

        <div class="content">
            <!-- Menu lateral -->
            <div class="sidebar">
                <ul>
                    <li>
                        <a id="button-green" class="button-green" href="#">Ocorrência</a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/registar_ocorrencia.php">Registar ocorrência</a></li>
                            <li><a href="/admin/listar_ocorrencias.php">Listar ocorrências</a></li>
                            <li><a href="/admin/notificar_semo.php">Notificar ocorrência</a></li>
                        </ul>
                    </li>
                    <li><a class="button-green" href="/admin/gerenciar_chefes.php">Gerenciar chefes do bairro</a></li>
                    <li><a class="button-green" href="/admin/gerenciar_agentes.php">Gerenciar agentes</a></li>
                    <li><a class="button-green" href="/admin/gerenciar_admin.php">Gerenciar administradores</a></li>
                </ul>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                function pesquisarOcorrencia() {
                    const query = $('#search_query').val();
                    $.ajax({
                        url: 'buscar_ocorrencias.php',
                        type: 'POST',
                        data: { search_query: query },
                        beforeSend: function () {
                            $('#resultado_ocorrencias').html('<p>A carregar...</p>');
                        },
                        success: function (response) {
                            $('#resultado_ocorrencias').html(response);
                            limitarDescricao();
                        },
                        error: function () {
                            $('#resultado_ocorrencias').html('<p>Erro ao carregar ocorrências.</p>');
                        }
                    });
                }

                $(document).ready(function () {
                    pesquisarOcorrencia(); // Carrega por defeito
                });
            </script>

            <!-- Conteúdo principal -->
              <div class="main-list">
                  <h3>Lista de ocorrências</h3>

                  <div class="add-button-container">
                      <form class="search-form" onsubmit="return false;">
                          <input type="text" id="search_query" placeholder="Buscar ocorrência..." oninput="pesquisarOcorrencia()">
                      </form>
                  </div>

                  <div id="resultado_ocorrencias"></div>
              </div>

        </div>
    </div>

    <!-- Scripts -->
    <script>

        // Mostrar/ocultar menu suspenso
        document.querySelectorAll('.sidebar #button-green').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var dropdown = this.parentElement.querySelector('.dropdown-menu');
                dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
            });
        });

        // Limitar descrição
        function limitarDescricao() {
            var descricaoCells = document.querySelectorAll(".reduz");
            var maxLen = 20;
            descricaoCells.forEach(function(cell) {
                if (cell.textContent.length > maxLen) {
                    cell.innerHTML = cell.textContent.substring(0, maxLen) + "...";
                }
            });
        }
        window.addEventListener("load", limitarDescricao);

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

  #resultado{
    width: 100%;
  }

  .search-form input {
    width: 130px;
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

  .main-list {
    width: 59vw;
    height: 100%;
    padding: 15px;
    background-color: #48ed;
    box-shadow: 0 0 10px rgb(59, 58, 58);
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    overflow-y: auto;
  }

  h3, tr{
    color: white;
  }
  td{
    color: black;
  }

  .occurrences-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #ccc;
    margin-top: 21px;
    box-shadow: 0 0 10px rgb(59, 58, 58);
  }

  .occurrences-table th {
    background-color: rgb(55, 142, 65);
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
  }

  .occurrences-table td {
    border: 1px solid #ccc;
    padding: 8px;
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

  /* Mensagem de erro */
  .error-message {
    background-color: #ffdddd;
    color: #d8000c;
    border: 1px solid #d8000c;
    padding: 10px;
    margin: 10px auto;
    width: 50%;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    font-family: 'Times New Roman', Times, serif;
    box-shadow: 0px 0px 5px rgba(216, 0, 12, 0.5);
    animation: fadeIn 0.5s ease-in-out;
  }

  /* Animação de entrada */
  @keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
  }

</style>
