<?php
session_start();
include '../includes/conexao.php';

// Verifica se o ID da ocorrência foi fornecido via GET
if (!isset($_GET['id'])) {
      echo '
      <div class="error-container">
      <h3>Erro: Nenhum número de ocorrência fornecido.</h3>
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
      exit;
  }

  $id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM tbl_ocorrencia WHERE id = ?");
  $stmt->execute([$id]);
  $ocorrencia = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$ocorrencia) {
      echo "<h3>Nenhuma ocorrência encontrada com este número.</h3>";
      exit;
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Visualizar Ocorrência - PRM | MuseiwaProg</title>
  <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div class="container">
    <header class="header">
      <button id="menu-toggle" class="menu-toggle">☰ Menu</button>
      <h2> [Usuário] - PRM Vilankulo</h2>
      <div class="profile-options">
        <?php
          $contacto = isset($_SESSION['contacto_cidadao']) ? urlencode($_SESSION['contacto_cidadao']) : '';
        ?>
          <a href="listar_ocorrencias.php?id=<?= $contacto ?>" id="visualizar">⬅️ Voltar</a>
        <!-- <a href="listar_ocorrencias.php?id=<?= $contacto ?>" id="sair">Voltar</a> -->
      </div>
    </header>
    <div class="content">
      <div class="sidebar">
        <div class="info-system">
          <h4>Sobre o Sistema de Registo de Ocorrências</h4>
          <p>Este sistema permite registar ocorrências de maneira simples e eficiente, visando garantir o correcto acompanhamento das denúncias feitas à Polícia da República de Moçambique - Vilankulo (PRM).</p>
          <p>____________________________</p>
          <p><strong>⚠️Atenção:</strong></p>
          <p>Ao registar uma ocorrência, você se compromete a fornecer informações verdadeiras. Registar informações falsas ou enganosas pode ser punível de acordo com a legislação vigente, com sérias consequências legais. Portanto, por favor, seja responsável e forneça dados precisos.</p>
        </div>
      </div>
      <div class="main-form">
        <div class="div-1">
          <h3>Dados da Ocorrência</h3>
          <div class="container-1">
            <div class="d1">
              <label>Nome do cidadão:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['nome_cidadao']) ?>" readonly>
            </div>
            <div class="d2">
              <label>Sexo:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['sexo_cidadao']) ?>" readonly>
            </div>
          </div>
          <div class="container-2">
            <div class="d3">
              <label>Estado civil:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['estado_civil']) ?>" readonly>
            </div>
            <div class="d4">
              <label>Idade:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['idade_cidadao']) ?>" readonly>
            </div>
          </div>
          <div class="container-3">
            <div class="d5">
              <label>Mãe:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['mae_cidadao']) ?>" readonly>
            </div>
            <div class="d6">
              <label>Pai:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['pai_cidadao']) ?>" readonly>
            </div>
          </div>
          <div class="container-4">
            <div class="d7">
              <label>Data de nascimento:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['data_nascimento']) ?>" readonly>
            </div>
            <div class="d8">
              <label>Naturalidade (Distrito):</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['naturalidade_distrito']) ?>" readonly>
            </div>
          </div>
          <div class="container-5">
            <div class="d9">
              <label>Província:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['provincia']) ?>" readonly>
            </div>
            <div class="d10">
              <label>Nacionalidade:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['nacionalidade']) ?>" readonly>
            </div>
          </div>
          <div class="container-6">
            <div class="d11">
              <label>Endereço do trabalho:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['local_trabalho']) ?>" readonly>
            </div>
            <div class="d12">
              <label>Bairro:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['bairro']) ?>" readonly>
            </div>
          </div>
          <!-- <div class="container-7">
            <div class="d13">
              <label>Chefe do bairro:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['chefe_bairro']) ?>" readonly>
            </div>
            <div class="d14">
              <label>Contacto do chefe do bairro:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['contacto_chefe_bairro']) ?>" readonly>
            </div>
          </div> -->
          <div class="container-8">
            <div class="d15">
              <label>Contacto do cidadão:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['contacto_cidadao']) ?>" readonly>
            </div>
            <div class="d16">
              <label>Classificação:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['classificacao']) ?>" readonly>
            </div>
          </div>
        </div>
        <div class="div-2">
          <h3>Características do Caso</h3>
          <div class="container-9">
            <div class="d17">
              <label>Data do ocorrido:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['data_ocorrido']) ?>" readonly>
            </div>
            <div class="d18">
              <label>Hora do ocorrido:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['hora_ocorrido']) ?>" readonly>
            </div>
          </div>
          <div class="container-10">
            <div class="d19">
              <label>Lugar do ocorrido:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['lugar_ocorrido']) ?>" readonly>
            </div>
            <div class="d20">
              <label>Endereço do caso:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['endereco_caso']) ?>" readonly>
            </div>
          </div>
          <div class="container-11">
            <div class="d21">
              <label>Nome do visado:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['nome_visado']) ?>" readonly>
            </div>
            <div class="d22">
              <label>Contacto do visado:</label>
              <input type="text" value="<?= htmlspecialchars($ocorrencia['contacto_visado']) ?>" readonly>
            </div>
          </div>
          <div class="container-12">
            <div class="d23">
              <label>Descrição do ocorrido:</label>
              <textarea readonly><?= htmlspecialchars($ocorrencia['descricao_ocorrido']) ?></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Mostrar/ocultar sidebar no mobile
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    menuToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      sidebar.classList.toggle('sidebar-open');
    });
    document.addEventListener('click', function(e) {
      if (window.innerWidth <= 900 && sidebar.classList.contains('sidebar-open')) {
        if (!sidebar.contains(e.target) && e.target !== menuToggle) {
          sidebar.classList.remove('sidebar-open');
        }
      }
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
    /* text-align: justify; */
  }

  .sidebar ul {
    list-style: none;
    padding: 8px;
  }

  .sidebar li {
    margin-bottom: 15px;
  }

    h4 {
    text-align: justify;
  }

  p {
    text-align: justify;
  }

  .info-system {
    height: 100%;
    color: white;
    padding: 15px;
    border-radius: 5px;
    margin-top: 10px;
  }

  .main-form {
    width: 59vw;
    height: 100%;
    padding: 15px;
    background-color: #ffffffce;
    background-color: #48ed;
    border-radius: 5px;
    display: flex;
    flex-direction: row; /* Lado a lado por padrão */
    align-items: flex-start;
    justify-content: center;
    box-shadow: 0 0 10px rgb(59, 58, 58);
    overflow-y: auto;
  }

  .div-1, .div-2 {
    width: 50%;
    min-width: 300px;
    height: 100%;
    box-sizing: border-box;
    padding: 10px;
  }

  @media (max-width: 900px) {
    body {
      justify-content: flex-start !important;
      align-items: flex-start !important;
      padding: 0 !important;
      margin: 0 !important;
    }
    .container {
      margin: 0 !important;
      padding: 0 !important;
      width: 100vw !important;
      max-width: 100vw !important;
    }
    .main-form {
      width: 100% !important;
      max-width: 100vw !important;
      margin: 0 auto !important;
      box-sizing: border-box;
    }
    body {
      height: auto !important;
      min-height: 100vh;
      overflow-y: auto !important;
    }
    .container {
      height: auto !important;
      min-height: 100vh;
      overflow-y: visible !important;
    }
    .content {
      height: auto !important;
      min-height: unset;
      overflow-y: visible !important;
    }
    .main-form {
      height: auto !important;
      max-height: unset;
      overflow-y: visible !important;
    }
    .content {
      flex-direction: column;
      align-items: stretch;
    }
    .sidebar {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 90vw;
      max-width: 350px;
      height: 100vh;
      z-index: 1500;
      background: #333;
      border-radius: 0 10px 10px 0;
      box-shadow: 2px 0 10px rgb(59, 58, 58);
      overflow-y: auto;
      transition: transform 0.3s ease, opacity 0.3s;
      transform: translateX(-100%);
      opacity: 0;
    }
    .sidebar.sidebar-open {
      display: block;
      transform: translateX(0);
      opacity: 1;
    }
    .main-form {
      width: 100vw;
      max-width: 100vw;
      margin: 0 auto;
    }
    .menu-toggle {
      display: block;
      position: static;
      margin-right: 10px;
      margin-left: 0;
    }
    .header {
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 10px 5px 10px 5px !important;
      gap: 8px;
      text-align: center;
    }
    .header h2 {
      margin: 0 0 5px 0 !important;
      font-size: 1.2em !important;
      word-break: break-word;
    }
    .profile-options {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      width: 100%;
    }
    .profile-options a {
      margin: 0 !important;
      width: 90vw !important;
      max-width: 250px;
      min-width: 120px;
      text-align: center;
      font-size: 1em;
      white-space: normal;
      word-break: break-word;
    }
    .menu-toggle {
      align-self: flex-start;
      margin-bottom: 5px;
    }
    .main-form {
      flex-direction: column; /* Empilha no mobile */
      width: 98vw;
      min-width: unset;
      max-width: 100vw;
      height: auto;
      padding: 5px;
      margin-top: 0;
    }
    .div-1, .div-2 {
      width: 100%;
      min-width: unset;
      padding: 5px 0;
      margin-left: 18px;
    }
    .container-1, .container-2, .container-3, .container-4, .container-5, .container-6, .container-7, .container-8, .container-9, .container-10 {
      flex-direction: column;
      align-items: stretch;
      width: 100%;
    }
    .d1, .d2, .d3, .d4, .d5, .d6, .d7, .d8, .d9, .d10, .d11, .d12, .d13, .d14, .d15, .d16 {
      width: 100%;
      min-width: unset;
      max-width: unset;
    }
    .main-form input, .main-form select, .main-form textarea {
      width: 100% !important;
      min-width: unset;
      max-width: unset;
    }
  }

  @media (max-width: 600px) {
    .main-form {
      padding: 5px;
      gap: 8px;
    }
    .container-1, .container-2, .container-3, .container-4, .container-5, .container-6, .container-7, .container-8, .container-9, .container-10, .datetime-container {
      flex-direction: column;
      align-items: stretch;
      width: 100%;
    }
    .d1, .d2, .d3, .d4, .d5, .d6, .d7, .d8, .d9, .d10, .d11, .d12, .d13, .d14, .d15, .d16 {
      width: 100%;
      min-width: 0;
    }
    .main-form input, .main-form select, .main-form textarea {
      width: 100%;
      min-width: 0;
      max-width: 100%;
    }
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

  .d21 button{
    width: 90%;
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

  #visualizar {
    font-family: 'Times New Roman', Times, serif;
    font-size: 16px;
    background-color: rgb(55, 142, 65);
    border: 2px solid rgb(255, 255, 255);
    border-radius: 20px;
    padding: 5px 10px;
    font-weight: 600;
  }

  #visualizar:hover{
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

  .submit-button {
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
    margin-top: 5.4%;
    font-weight: 600;
    }

  .submit-button:hover{
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

  .menu-toggle {
    display: none;
    margin-right: 10px;
    background: #333;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 20px;
    font-family: 'Times New Roman', Times, serif;
    cursor: pointer;
    height: 40px;
    align-self: center;
  }

</style>
