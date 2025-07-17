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

            /* Adicione ao seu CSS global ou dentro do <style> */
            input:focus, select:focus, textarea:focus {
                border: 3px solid #378e41 !important;
                outline: none;
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_cidadao = $_POST["nome_cidadao"];
    $sexo_cidadao = $_POST["sexo_cidadao"];
    $estado_civil = $_POST["estado_civil"];
    $idade_cidadao = $_POST["idade_cidadao"];
    $mae_cidadao = $_POST["mae_cidadao"];
    $pai_cidadao = $_POST["pai_cidadao"];
    $data_nascimento = $_POST["data_nascimento"];
    $naturalidade_distrito = $_POST["naturalidade_distrito"];
    $provincia = $_POST["provincia"];
    $nacionalidade = $_POST["nacionalidade"];
    $local_trabalho = $_POST["local_trabalho"];
    $bairro = $_POST["bairro"] ?? "";
    $chefe_bairro = $_POST["chefe_bairro"] ?? "";
    $contacto_chefe_bairro = $_POST["contacto_chefe_bairro"] ?? "";
    $contacto_cidadao = $_POST["contacto_cidadao"] ?? "";
    $classificacao = $_POST["classificacao"] ?? "";
    $data_ocorrido = $_POST["data_ocorrido"] ?? "";
    $hora_ocorrido = $_POST["hora_ocorrido"] ?? "";
    $lugar_ocorrido = $_POST["lugar_ocorrido"] ?? "";
    $endereco_caso = $_POST["endereco_caso"] ?? "";
    $nome_visado = $_POST["nome_visado"] ?? "";
    $contacto_visado = $_POST["contacto_visado"] ?? "";
    $descricao_ocorrido = $_POST["descricao_ocorrido"] ?? "";
    $estado = "Notificar";

  try {
      $sql = "INSERT INTO tbl_ocorrencia (nome_cidadao, sexo_cidadao, estado_civil, idade_cidadao, mae_cidadao, pai_cidadao, data_nascimento, naturalidade_distrito, provincia, nacionalidade, local_trabalho, bairro, chefe_bairro, contacto_chefe_bairro, contacto_cidadao, classificacao, data_ocorrido, hora_ocorrido, lugar_ocorrido, endereco_caso, nome_visado, contacto_visado, descricao_ocorrido, estado)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

      $stmt = $conn->prepare($sql);

      if ($stmt->execute([$nome_cidadao, $sexo_cidadao, $estado_civil, $idade_cidadao, $mae_cidadao, $pai_cidadao, $data_nascimento, $naturalidade_distrito, $provincia, $nacionalidade, $local_trabalho, $bairro, $chefe_bairro, $contacto_chefe_bairro, $contacto_cidadao, $classificacao, $data_ocorrido, $hora_ocorrido, $lugar_ocorrido, $endereco_caso, $nome_visado, $contacto_visado, $descricao_ocorrido, $estado])) {
          $_SESSION['mensagem'] = "✅ Ocorrência registada com sucesso!";
          $_SESSION['tipo_mensagem'] = "sucesso";
      } else {
          $_SESSION['mensagem'] = "❌ Erro ao registar ocorrência.";
          $_SESSION['tipo_mensagem'] = "erro";
      }

      header("Location: sucesso_ocorrencia_registada.php");
      exit();
  } catch (PDOException $e) {
      $_SESSION['mensagem'] = "❌ Erro ao registar ocorrência: " . $e->getMessage();
      $_SESSION['tipo_mensagem'] = "erro";
      header("Location: sucessoocorrenciaregistada.php");
      exit();
  }
}

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

<div class="main-form">

        <div class="div-1">
          <form action="" method="POST">

          <h3>(Classificação do caso)</h3>

          <div class="container-1">
              <div class="d1">
                <label for="nome_cidadao">Nome do cidadão:</label>
                <input type="text" id="nome_cidadao" name="nome_cidadao" required placeholder="Digite o nome e o apelido">
              </div>

              <div class="d2">
              <label for="sexo_cidadão">Sexo do cidadão:</label>
              <select name="sexo_cidadao" id="sexo_cidadao">
              <option disabled selected hidden>Seleccione o sexo</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
              </div>
          </div>

          <div class="container-2">
            <div class="d3">
              <label for="estado_civil">Estado civil do cidadão:</label>
              <select name="estado_civil" id="estado_civil">
                <option disabled selected hidden>Seleccione o estado civil</option>
                  <option value="Solteiro(a)">Solteiro(a)</option>
                  <option value="Casado(a)">Casado(a)</option>
              </select>
            </div>

            <div class="d4">
              <label for="idade_cidadao">Idade do cidadão:</label>
              <input type="number" id="idade_cidadao" name="idade_cidadao" maxlength="2" min="18" required placeholder="Digite a idade do cidadão">
            </div>
          </div>

          <div class="container-3">
            <div class="d5">
              <label for="mae">Mãe do cidadão:</label>
              <input type="text" id="mae_cidadao" name="mae_cidadao" placeholder="Digite o nome da mãe">
            </div>

            <div class="d6">
              <label for="pai_cidadao">Pai do cidadão:</label>
              <input type="text" id="pai_cidadao" name="pai_cidadao" placeholder="Digite o nome do pai">
            </div>
          </div>

          <div class="container-4">
            <div class="d7">
              <label for="data_nascimento">Data de nascimento:</label>
              <input type="date" id="data_nascimento" name="data_nascimento">
            </div>

            <div class="d8">
              <label for="naturalidade_distrito">Naturalidade (Distrito):</label>
              <select id="naturalidade_distrito" name="naturalidade_distrito" required>
                <option disabled selected hidden>Seleccione o distrito</option>

                <optgroup label="Maputo (Cidade)">
                  <option value="KaMpfumo">KaMpfumo</option>
                  <option value="Nlhamankulu">Nlhamankulu</option>
                  <option value="KaMaxaquene">KaMaxaquene</option>
                  <option value="KaMavota">KaMavota</option>
                  <option value="KaTembe">KaTembe</option>
                  <option value="KaNyaka">KaNyaka</option>
                </optgroup>

                <optgroup label="Maputo (Província)">
                  <option value="Boane">Boane</option>
                  <option value="Magude">Magude</option>
                  <option value="Manhiça">Manhiça</option>
                  <option value="Marracuene">Marracuene</option>
                  <option value="Matola">Matola (Cidade)</option>
                  <option value="Matutuíne">Matutuíne</option>
                  <option value="Moamba">Moamba</option>
                  <option value="Namaacha">Namaacha</option>
                </optgroup>

                <optgroup label="Gaza">
                  <option value="Bilene">Bilene</option>
                  <option value="Chibuto">Chibuto</option>
                  <option value="Chicualacuala">Chicualacuala</option>
                  <option value="Chigubo">Chigubo</option>
                  <option value="Chókwe">Chókwe</option>
                  <option value="Guijá">Guijá</option>
                  <option value="Limpopo">Limpopo</option>
                  <option value="Mabalane">Mabalane</option>
                  <option value="Manjacaze">Manjacaze</option>
                  <option value="Massagena">Massagena</option>
                  <option value="Massingir">Massingir</option>
                  <option value="Xai-Xai">Xai-Xai (Cidade)</option>
                </optgroup>

                <optgroup label="Inhambane">
                  <option value="Funhalouro">Funhalouro</option>
                  <option value="Govuro">Govuro</option>
                  <option value="Homoíne">Homoíne</option>
                  <option value="Inharrime">Inharrime</option>
                  <option value="Inhassoro">Inhassoro</option>
                  <option value="Jangamo">Jangamo</option>
                  <option value="Massinga">Massinga</option>
                  <option value="Morrumbene">Morrumbene</option>
                  <option value="Panda">Panda</option>
                  <option value="Vilankulo">Vilankulo</option>
                  <option value="Zavala">Zavala</option>
                  <option value="Inhambane">Inhambane (Cidade)</option>
                </optgroup>

                <optgroup label="Sofala">
                  <option value="Beira">Beira (Cidade)</option>
                  <option value="Búzi">Búzi</option>
                  <option value="Caia">Caia</option>
                  <option value="Chemba">Chemba</option>
                  <option value="Cheringoma">Cheringoma</option>
                  <option value="Chibabava">Chibabava</option>
                  <option value="Dondo">Dondo</option>
                  <option value="Gorongosa">Gorongosa</option>
                  <option value="Machanga">Machanga</option>
                  <option value="Marínguè">Marínguè</option>
                  <option value="Marromeu">Marromeu</option>
                  <option value="Muanza">Muanza</option>
                  <option value="Nhamatanda">Nhamatanda</option>
                </optgroup>

                <optgroup label="Manica">
                  <option value="Barué">Barué</option>
                  <option value="Chimoio">Chimoio (Cidade)</option>
                  <option value="Gondola">Gondola</option>
                  <option value="Guro">Guro</option>
                  <option value="Macate">Macate</option>
                  <option value="Machaze">Machaze</option>
                  <option value="Macossa">Macossa</option>
                  <option value="Manica">Manica</option>
                  <option value="Mossurize">Mossurize</option>
                  <option value="Sussundenga">Sussundenga</option>
                  <option value="Tambara">Tambara</option>
                  <option value="Vanduzi">Vanduzi</option>
                </optgroup>

                <optgroup label="Nampula">
                  <option value="Angoche">Angoche</option>
                  <option value="Eráti">Eráti</option>
                  <option value="Ilha de Moçambique">Ilha de Moçambique</option>
                  <option value="Lalaua">Lalaua</option>
                  <option value="Malema">Malema</option>
                  <option value="Meconta">Meconta</option>
                  <option value="Mecubúri">Mecubúri</option>
                  <option value="Memba">Memba</option>
                  <option value="Mogincual">Mogincual</option>
                  <option value="Mogovolas">Mogovolas</option>
                  <option value="Moma">Moma</option>
                  <option value="Monapo">Monapo</option>
                  <option value="Mossuril">Mossuril</option>
                  <option value="Nacala-a-Velha">Nacala-a-Velha</option>
                  <option value="Nacala-Porto">Nacala-Porto (Cidade)</option>
                  <option value="Nampula">Nampula (Cidade)</option>
                  <option value="Rapale">Rapale</option>
                  <option value="Ribáuè">Ribáuè</option>
                </optgroup>

                <optgroup label="Niassa">
                  <option value="Cuamba">Cuamba</option>
                  <option value="Lago">Lago</option>
                  <option value="Lichinga">Lichinga (Cidade)</option>
                  <option value="Majune">Majune</option>
                  <option value="Mandimba">Mandimba</option>
                  <option value="Marrupa">Marrupa</option>
                  <option value="Maúa">Maúa</option>
                  <option value="Mavago">Mavago</option>
                  <option value="Mecanhelas">Mecanhelas</option>
                  <option value="Metarica">Metarica</option>
                  <option value="Muembe">Muembe</option>
                  <option value="N'gauma">N'gauma</option>
                  <option value="Sanga">Sanga</option>
                </optgroup>

                <optgroup label="Tete">
                  <option value="Angónia">Angónia</option>
                  <option value="Cahora-Bassa">Cahora-Bassa</option>
                  <option value="Changara">Changara</option>
                  <option value="Chifunde">Chifunde</option>
                  <option value="Chiuta">Chiuta</option>
                  <option value="Doa">Doa</option>
                  <option value="Macanga">Macanga</option>
                  <option value="Magoe">Magoe</option>
                  <option value="Marávia">Marávia</option>
                  <option value="Moatize">Moatize</option>
                  <option value="Mutarara">Mutarara</option>
                  <option value="Tete">Tete (Cidade)</option>
                  <option value="Zumbu">Zumbu</option>
                </optgroup>

                <optgroup label="Zambézia">
                  <option value="Alto Molócuè">Alto Molócuè</option>
                  <option value="Chinde">Chinde</option>
                  <option value="Gilé">Gilé</option>
                  <option value="Guruè">Guruè</option>
                  <option value="Ile">Ile</option>
                  <option value="Inhassunge">Inhassunge</option>
                  <option value="Luabo">Luabo</option>
                  <option value="Lugela">Lugela</option>
                  <option value="Maganja da Costa">Maganja da Costa</option>
                  <option value="Milange">Milange</option>
                  <option value="Mocuba">Mocuba</option>
                  <option value="Mopeia">Mopeia</option>
                  <option value="Namacurra">Namacurra</option>
                  <option value="Quelimane">Quelimane (Cidade)</option>
                </optgroup>

                <optgroup label="Internacional">
                  <option value="Outro">Outro</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="container-5">
            <div class="d9">
              <label for="provincia">Província:</label>
              <select id="provincia" name="provincia" readonly>
                <option disabled selected hidden>Seleccione a província</option>
                <option value="Cabo Delgado">Cabo Delgado</option>
                <option value="Gaza">Gaza</option>
                <option value="Inhambane">Inhambane</option>
                <option value="Manica">Manica</option>
                <option value="Maputo">Maputo</option>
                <option value="Nampula">Nampula</option>
                <option value="Niassa">Niassa</option>
                <option value="Sofala">Sofala</option>
                <option value="Tete">Tete</option>
                <option value="Zambezia">Zambezia</option>
                <option value="Outro">Outro</option>
              </select>
            </div>

            <div class="d10">
              <label for="nacionalidade">Nacionalidade:</label>
              <select id="nacionalidade" name="nacionalidade" readonly>
                <option disabled selected hidden>Seleccione a nacionalidade</option>
                <option value="Moçambicana">Moçambicana</option>
                <option value="Sul-Africana">Sul-Africana</option>
                <option value="Zimbabueana">Zimbabueana</option>
                <option value="Angolana">Angolana</option>
                <option value="Brasileira">Brasileira</option>
                <option value="Chinesa">Chinesa</option>
                <option value="Indiana">Indiana</option>
                <option value="Tailandesa">Tailandesa</option>
                <option value="Suazi">Suazi (ou eSwatini)</option>
                <option value="Outro">Outro</option>
              </select>
            </div>
          </div>

            <div class="container-6">
              <div class="d11">
                <label for="local_trabalho">Endereço do trabalho:</label>
                <input type="text" id="local_trabalho" name="local_trabalho" placeholder="Digite o endereço do local">
              </div>

              <div class="d12">
                <label for="bairro">Residente do bairro:</label>
                  <select id="bairro" name="bairro" required onchange="preencherChefeBairro()">
                    <option disabled selected hidden>Seleccione o bairro</option>
                    <?php
                    include '../includes/conexao.php';

                    $sql = "SELECT * FROM tbl_chefe_bairro";
                    $stmt = $conn->query($sql);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo '<option data-chefe="' . $row['nome_completo'] . '" data-contato="' . $row['contacto'] . '">' . $row['bairro'] . '</option>';
                    }

                    $conn = null;
                    ?>
                  </select>
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
              <input type="number" id="contacto_cidadao" name="contacto_cidadao" maxlength="9" required placeholder="Digite o contacto">
            </div>
            <div class="d14">
              <label for="classificacao">Classificação:</label>
              <select id="classificacao" name="classificacao" required>
                <option disabled selected hidden>Seleccione o tipo de ocorrência:</option>
                <option value="Violência">Violência</option>
                <option value="Agressão">Agressão</option>
                <option value="Roubo">Roubo</option>
                <option value="Acidente">Acidente</option>
                <option value="Outro">Outro</option>
              </select>
            </div>
          </div>
        </div>

        <div class="div-2">

          <h3>(Características do caso)</h3>

          <div class="container-8">
            <div class="d15">
              <label for="data_ocorrido">Data do ocorrido:</label>
              <input type="date" id="data_ocorrido" name="data_ocorrido">
            </div>

            <div class="d16">
              <label for="hora_ocorrido">Hora do ocorrido</label>
              <input type="time" id="hora_ocorrido" name="hora_ocorrido">
            </div>
          </div>

          <div class="container-9">
            <div class="d17">
              <label for="lugar_ocorrido">Lugar do ocorrido:</label>
              <input type="text" id="lugar_ocorrido" name="lugar_ocorrido" required placeholder="Digite o lugar da ocorrência">
            </div>

            <div class="d18">
              <label for="endereco_caso">Endereço do caso:</label>
              <input type="text" id="endereco_caso" name="endereco_caso" placeholder="Digite o endereço do caso">
            </div>
          </div>

          <div class="container-10">
            <div class="d19">
              <label for="visado">Nome do visado:</label>
              <input type="text" id="nome_visado" name="nome_visado" placeholder="Digite o nome e o apelido">
            </div>

            <div class="d20">
              <label for="contacto_visado">Contacto do visado:</label>
              <input type="number" id="contacto_visado" name="contacto_visado" placeholder="Digite o contacto">
            </div>
          </div>

          <div class="d19">
            <label for="descricao">Descrição do ocorrido:</label>
            <textarea id="descricao_ocorrido" name="descricao_ocorrido" placeholder="Digite aqui..." rows="" required></textarea>
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

          <div class="d20">
              <button class="submit-button" type="submit">Registar</button>
          </div>

          <script>
          function preencherChefeBairro() {
            var select = document.getElementById('bairro');
            var chefeBairroInput = document.getElementById('chefe_bairro');
            var contactoChefeBairroInput = document.getElementById('contacto_chefe_bairro');

            var selectedOption = select.options[select.selectedIndex];
            var chefeBairro = selectedOption.getAttribute('data-chefe');
            var contactoChefeBairro = selectedOption.getAttribute('data-contato');

            chefeBairroInput.value = chefeBairro;
            contactoChefeBairroInput.value = contactoChefeBairro;

            // Remova a definição "disabled" dos campos
            chefeBairroInput.removeAttribute('disabled');
            contactoChefeBairroInput.removeAttribute('disabled');
          }
          </script>

          </form>
        </div>

      </div>
    </div>

  </div>
  <script>
    // Função para actualizar o campo de contato do chefe do bairro quando uma opção é selecionada
    document.getElementById('chefe_bairro').addEventListener('change', function() {
      var selectedOption = this.options[this.selectedIndex];
      var contactoChefeBairro = selectedOption.textContent.match(/\(([^)]+)\)/)[1];
      document.getElementById('contacto_chefe_bairro').value = contactoChefeBairro;
    });
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

          <script>
          (function(){
            const distritoEl  = document.getElementById('naturalidade_distrito');
            const provinciaEl = document.getElementById('provincia');
            const nacioEl     = document.getElementById('nacionalidade');

            const mapaProv = {
              "Ancuabe": "Cabo Delgado", "Balama": "Cabo Delgado", "Chiúre": "Cabo Delgado",
              "Ibo": "Cabo Delgado", "Macomia": "Cabo Delgado", "Mecúfi": "Cabo Delgado",
              "Meluco": "Cabo Delgado", "Mocímboa da Praia": "Cabo Delgado", "Montepuez": "Cabo Delgado",
              "Mueda": "Cabo Delgado", "Muidumbe": "Cabo Delgado", "Namuno": "Cabo Delgado",
              "Nangade": "Cabo Delgado", "Palma": "Cabo Delgado", "Pemba": "Cabo Delgado",

              "Bilene": "Gaza", "Chibuto": "Gaza", "Chicualacuala": "Gaza", "Chigubo": "Gaza",
              "Chókwe": "Gaza", "Guijá": "Gaza", "Limpopo": "Gaza", "Mabalane": "Gaza",
              "Manjacaze": "Gaza", "Massagena": "Gaza", "Massingir": "Gaza", "Xai-Xai": "Gaza",

              "Funhalouro": "Inhambane", "Govuro": "Inhambane", "Homoíne": "Inhambane",
              "Inharrime": "Inhambane", "Inhassoro": "Inhambane", "Jangamo": "Inhambane",
              "Massinga": "Inhambane", "Morrumbene": "Inhambane", "Panda": "Inhambane",
              "Vilankulo": "Inhambane", "Zavala": "Inhambane", "Inhambane": "Inhambane",

              "Barué": "Manica", "Chimoio": "Manica", "Gondola": "Manica", "Guro": "Manica",
              "Macate": "Manica", "Machaze": "Manica", "Macossa": "Manica", "Manica": "Manica",
              "Mossurize": "Manica", "Sussundenga": "Manica", "Tambara": "Manica", "Vanduzi": "Manica",

              "Boane": "Maputo", "Magude": "Maputo", "Manhiça": "Maputo", "Marracuene": "Maputo",
              "Matola": "Maputo", "Matutuíne": "Maputo", "Moamba": "Maputo", "Namaacha": "Maputo",

              "KaMpfumo": "Maputo", "Nlhamankulu": "Maputo", "KaMaxaquene": "Maputo",
              "KaMavota": "Maputo", "KaTembe": "Maputo", "KaNyaka": "Maputo",

              "Angoche": "Nampula", "Eráti": "Nampula", "Ilha de Moçambique": "Nampula",
              "Lalaua": "Nampula", "Malema": "Nampula", "Meconta": "Nampula", "Mecubúri": "Nampula",
              "Memba": "Nampula", "Mogincual": "Nampula", "Mogovolas": "Nampula", "Moma": "Nampula",
              "Monapo": "Nampula", "Mossuril": "Nampula", "Nacala-a-Velha": "Nampula",
              "Nacala-Porto": "Nampula", "Nampula": "Nampula", "Rapale": "Nampula", "Ribáuè": "Nampula",

              "Cuamba": "Niassa", "Lago": "Niassa", "Lichinga": "Niassa", "Majune": "Niassa",
              "Mandimba": "Niassa", "Marrupa": "Niassa", "Maúa": "Maúa", "Mavago": "Niassa",
              "Mecanhelas": "Niassa", "Metarica": "Niassa", "Muembe": "Niemba", "N'gauma": "Niassa",
              "Sanga": "Niassa",

              "Beira": "Sofala", "Búzi": "Sofala", "Caia": "Sofala", "Chemba": "Sofala",
              "Cheringoma": "Sofala", "Chibabava": "Sofala", "Dondo": "Sofala",
              "Gorongosa": "Sofala", "Machanga": "Sofala", "Marínguè": "Sofala",
              "Marromeu": "Sofala", "Muanza": "Sofala", "Nhamatanda": "Sofala",

              "Angónia": "Tete", "Cahora-Bassa": "Tete", "Changara": "Tete", "Chifunde": "Tete",
              "Chiuta": "Tete", "Doa": "Tete", "Macanga": "Tete", "Magoe": "Tete",
              "Marávia": "Tete", "Moatize": "Tete", "Mutarara": "Tete", "Tete": "Tete",
              "Zumbu": "Tete",

              "Alto Molócuè": "Zambezia", "Chinde": "Zambezia", "Gilé": "Zambezia", "Guruè": "Zambezia",
              "Ile": "Zambezia", "Inhassunge": "Zambezia", "Luabo": "Zambezia", "Lugela": "Zambezia",
              "Maganja da Costa": "Zambezia", "Milange": "Zambezia", "Mocuba": "Zambezia",
              "Mopeia": "Zambezia", "Namacurra": "Zambezia", "Quelimane": "Zambezia"
            };

            distritoEl.addEventListener('change', function(){
              const d = this.value;
              if (d === 'Outro') {
                provinciaEl.value = 'Outro';
                nacioEl.value = 'Outro';
                provinciaEl.removeAttribute('readonly');
                nacioEl.removeAttribute('readonly');
              } else {
                provinciaEl.value = mapaProv[d] || '';
                nacioEl.value = 'Moçambicana';
                provinciaEl.setAttribute('readonly', true);
                nacioEl.setAttribute('readonly', true);
              }
            });
          })();
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

  input:focus, select:focus, textarea:focus {
    border: 3px solid #378e41 !important;
    outline: none !important;
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
