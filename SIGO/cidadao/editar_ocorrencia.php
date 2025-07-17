<?php
session_start();
session_regenerate_id(true); // Regenera o ID da sessão para maior segurança

// Verifica se o usuário está logado e é cidadão
if (!isset($_SESSION["user"]) || $_SESSION["tipo_usuario"] !== "Cidadao") {
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
            <a href="logout.php" class="error-button">Fazer Login!</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

include '../includes/conexao.php';

$id = $_SESSION["id"];
$stmt = $conn->prepare("SELECT * FROM tbl_cidadao WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();

// Se não encontrar o usuário, redireciona para o login
if ($stmt->rowCount() === 0) {
    header("Location: ../logout.php");
    exit();
}

$user = $stmt->fetch(PDO::FETCH_OBJ);

  // Verificar se o ID foi fornecido para atualização
  if (isset($_GET['id'])) {
      $id = $_GET['id'];

      // Buscar os dados da ocorrência com base no ID
      $sql = "SELECT * FROM tbl_ocorrencia WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$id]);
      $ocorrencia = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$ocorrencia) {
          echo "Ocorrência não encontrada.";
          exit;
      }
  } else {
      echo "ID da ocorrência não fornecido.";
      exit;
  }

  // Verificar se o formulário foi enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nome_cidadao           = $_POST["nome_cidadao"];
      $sexo_cidadao           = $_POST["sexo_cidadao"];
      $estado_civil           = $_POST["estado_civil"];
      $idade_cidadao          = $_POST["idade_cidadao"];
      $mae_cidadao            = $_POST["mae_cidadao"];
      $pai_cidadao            = $_POST["pai_cidadao"];
      $data_nascimento        = $_POST["data_nascimento"];
      $naturalidade_distrito  = $_POST["naturalidade_distrito"];
      $provincia              = $_POST["provincia"];
      $nacionalidade          = $_POST["nacionalidade"];
      $local_trabalho         = $_POST["local_trabalho"];
      $bairro                 = $_POST["bairro"] ?? "";
      $chefe_bairro           = $_POST["chefe_bairro"] ?? "";
      $contacto_chefe_bairro  = $_POST["contacto_chefe_bairro"] ?? "";
      $contacto_cidadao       = $_POST["contacto_cidadao"] ?? "";
      $classificacao          = $_POST["classificacao"] ?? "";
      $data_ocorrido          = $_POST["data_ocorrido"] ?? "";
      $hora_ocorrido          = $_POST["hora_ocorrido"] ?? "";
      $lugar_ocorrido         = $_POST["lugar_ocorrido"] ?? "";
      $endereco_caso          = $_POST["endereco_caso"] ?? "";
      $nome_visado            = $_POST["nome_visado"] ?? "";
      $contacto_visado        = $_POST["contacto_visado"] ?? "";
      $descricao_ocorrido     = $_POST["descricao_ocorrido"] ?? "";
      $estado                 = "notificar";

      // Actualizar a ocorrência no banco de dados
      $update_query = "
          UPDATE tbl_ocorrencia SET
              nome_cidadao = ?, sexo_cidadao = ?, estado_civil = ?, idade_cidadao = ?,
              mae_cidadao = ?, pai_cidadao = ?, data_nascimento = ?, naturalidade_distrito = ?,
              provincia = ?, nacionalidade = ?, local_trabalho = ?, bairro = ?, chefe_bairro = ?,
              contacto_chefe_bairro = ?, contacto_cidadao = ?, classificacao = ?, data_ocorrido = ?,
              hora_ocorrido = ?, lugar_ocorrido = ?, endereco_caso = ?, nome_visado = ?,
              contacto_visado = ?, descricao_ocorrido = ?, estado = ?
          WHERE id = ?";

      $stmt = $conn->prepare($update_query);

      $params = [
          $nome_cidadao, $sexo_cidadao, $estado_civil, $idade_cidadao,
          $mae_cidadao, $pai_cidadao, $data_nascimento, $naturalidade_distrito,
          $provincia, $nacionalidade, $local_trabalho, $bairro, $chefe_bairro,
          $contacto_chefe_bairro, $contacto_cidadao, $classificacao, $data_ocorrido,
          $hora_ocorrido, $lugar_ocorrido, $endereco_caso, $nome_visado,
          $contacto_visado, $descricao_ocorrido, $estado, $id
      ];

      if ($stmt->execute($params)) {
          header('Location: sucesso_ocorrencia_actualizada.php?id=' . $id);
          exit;
      } else {
          $errorInfo = $stmt->errorInfo();
          echo "Erro ao actualizar a ocorrência: " . $errorInfo[2];
      }
  }
  ?>

<!DOCTYPE html>
<html>
<head>
  <title>Registar ocorrência - PRM | MuseiwaProg</title>
  <link rel="icon" type="image/png" href="../image/emblema_mocambique.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div class="container">
    <header class="header">
      <button id="menu-toggle" class="menu-toggle">☰ Menu</button>
      <h2> [Usuário] - PRM Vilankulo</h2>
      <div class="profile-options">
        <a href="actualizar_conta.php">
          <?php echo htmlspecialchars($user->nome_cidadao);?>
        </a>
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
          <form action="" method="POST">

          <h3>(Classificação do caso)</h3>

          <div class="container-1">
              <div class="d1">
                <label for="nome_cidadao">Nome do cidadão:</label>
               <input type="text" id="nome_cidadao" name="nome_cidadao" value="<?= $ocorrencia['nome_cidadao'] ?>" required placeholder="Digite o nome e o apelido">
              </div>

              <div class="d2">
              <label for="sexo_cidadão">Sexo do cidadão:</label>
              <select name="sexo_cidadao" id="sexo_cidadao">
              <option disabled selected hidden>Seleccione o sexo</option>
                <option value="Masculino" <?= $ocorrencia['sexo_cidadao'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="Femenino" <?= $ocorrencia['sexo_cidadao'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
              </select>
              </div>
          </div>

          <div class="container-2">
            <div class="d3">
              <label for="estado_civil">Estado civil do cidadão:</label>
              <select name="estado_civil" id="estado_civil">
                <option disabled selected hidden>Seleccione o estado civil</option>
                <option value="Solteiro(a)" <?= $ocorrencia['estado_civil'] == 'Solteiro(a)' ? 'selected' : '' ?>>Solteiro(a)</option>
                <option value="Casado(a)" <?= $ocorrencia['estado_civil'] == 'Casado(a)' ? 'selected' : '' ?>>Casado(a)</option>
              </select>
            </div>

            <div class="d4">
              <label for="idade_cidadao">Idade do cidadão:</label>
              <input type="number" id="idade_cidadao" name="idade_cidadao" maxlength="2" min="18" value="<?= $ocorrencia['idade_cidadao'] ?>" required placeholder="Digite a idade do cidadão">
            </div>
          </div>

          <div class="container-3">
            <div class="d5">
              <label for="mae">Mãe do cidadão:</label>
              <input type="text" id="mae_cidadao" name="mae_cidadao" value="<?= $ocorrencia['mae_cidadao'] ?>" placeholder="Digite o nome da mãe">
            </div>

            <div class="d6">
              <label for="pai_cidadao">Pai do cidadão:</label>
              <input type="text" id="pai_cidadao" name="pai_cidadao" value="<?= $ocorrencia['pai_cidadao'] ?>" placeholder="Digite o nome do pai">
            </div>
          </div>

          <div class="container-4">
            <div class="d7">
              <label for="data_nascimento">Data de nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $ocorrencia['data_nascimento'] ?>">
            </div>

            <div class="d8">
              <label for="naturalidade_distrito">Naturalidade (Distrito):</label>
              <select id="naturalidade_distrito" name="naturalidade_distrito" required>
                <option disabled hidden <?= empty($ocorrencia['naturalidade_distrito']) ? 'selected' : '' ?>>Seleccione o distrito</option>

                <optgroup label="Maputo (Cidade)">
                  <option value="KaMpfumo" <?= $ocorrencia['naturalidade_distrito'] == 'KaMpfumo' ? 'selected' : '' ?>>KaMpfumo</option>
                  <option value="Nlhamankulu" <?= $ocorrencia['naturalidade_distrito'] == 'Nlhamankulu' ? 'selected' : '' ?>>Nlhamankulu</option>
                  <option value="KaMaxaquene" <?= $ocorrencia['naturalidade_distrito'] == 'KaMaxaquene' ? 'selected' : '' ?>>KaMaxaquene</option>
                  <option value="KaMavota" <?= $ocorrencia['naturalidade_distrito'] == 'KaMavota' ? 'selected' : '' ?>>KaMavota</option>
                  <option value="KaTembe" <?= $ocorrencia['naturalidade_distrito'] == 'KaTembe' ? 'selected' : '' ?>>KaTembe</option>
                  <option value="KaNyaka" <?= $ocorrencia['naturalidade_distrito'] == 'KaNyaka' ? 'selected' : '' ?>>KaNyaka</option>
                </optgroup>

                <optgroup label="Maputo (Província)">
                  <option value="Boane" <?= $ocorrencia['naturalidade_distrito'] == 'Boane' ? 'selected' : '' ?>>Boane</option>
                  <option value="Magude" <?= $ocorrencia['naturalidade_distrito'] == 'Magude' ? 'selected' : '' ?>>Magude</option>
                  <option value="Manhiça" <?= $ocorrencia['naturalidade_distrito'] == 'Manhiça' ? 'selected' : '' ?>>Manhiça</option>
                  <option value="Marracuene" <?= $ocorrencia['naturalidade_distrito'] == 'Marracuene' ? 'selected' : '' ?>>Marracuene</option>
                  <option value="Matola" <?= $ocorrencia['naturalidade_distrito'] == 'Matola' ? 'selected' : '' ?>>Matola (Cidade)</option>
                  <option value="Matutuíne" <?= $ocorrencia['naturalidade_distrito'] == 'Matutuíne' ? 'selected' : '' ?>>Matutuíne</option>
                  <option value="Moamba" <?= $ocorrencia['naturalidade_distrito'] == 'Moamba' ? 'selected' : '' ?>>Moamba</option>
                  <option value="Namaacha" <?= $ocorrencia['naturalidade_distrito'] == 'Namaacha' ? 'selected' : '' ?>>Namaacha</option>
                </optgroup>

                <optgroup label="Gaza">
                  <option value="Bilene" <?= $ocorrencia['naturalidade_distrito'] == 'Bilene' ? 'selected' : '' ?>>Bilene</option>
                  <option value="Chibuto" <?= $ocorrencia['naturalidade_distrito'] == 'Chibuto' ? 'selected' : '' ?>>Chibuto</option>
                  <option value="Chicualacuala" <?= $ocorrencia['naturalidade_distrito'] == 'Chicualacuala' ? 'selected' : '' ?>>Chicualacuala</option>
                  <option value="Chigubo" <?= $ocorrencia['naturalidade_distrito'] == 'Chigubo' ? 'selected' : '' ?>>Chigubo</option>
                  <option value="Chókwe" <?= $ocorrencia['naturalidade_distrito'] == 'Chókwe' ? 'selected' : '' ?>>Chókwe</option>
                  <option value="Guijá" <?= $ocorrencia['naturalidade_distrito'] == 'Guijá' ? 'selected' : '' ?>>Guijá</option>
                  <option value="Limpopo" <?= $ocorrencia['naturalidade_distrito'] == 'Limpopo' ? 'selected' : '' ?>>Limpopo</option>
                  <option value="Mabalane" <?= $ocorrencia['naturalidade_distrito'] == 'Mabalane' ? 'selected' : '' ?>>Mabalane</option>
                  <option value="Manjacaze" <?= $ocorrencia['naturalidade_distrito'] == 'Manjacaze' ? 'selected' : '' ?>>Manjacaze</option>
                  <option value="Massagena" <?= $ocorrencia['naturalidade_distrito'] == 'Massagena' ? 'selected' : '' ?>>Massagena</option>
                  <option value="Massingir" <?= $ocorrencia['naturalidade_distrito'] == 'Massingir' ? 'selected' : '' ?>>Massingir</option>
                  <option value="Xai-Xai" <?= $ocorrencia['naturalidade_distrito'] == 'Xai-Xai' ? 'selected' : '' ?>>Xai-Xai (Cidade)</option>
                </optgroup>

                <optgroup label="Inhambane">
                  <option value="Funhalouro" <?= $ocorrencia['naturalidade_distrito'] == 'Funhalouro' ? 'selected' : '' ?>>Funhalouro</option>
                  <option value="Govuro" <?= $ocorrencia['naturalidade_distrito'] == 'Govuro' ? 'selected' : '' ?>>Govuro</option>
                  <option value="Homoíne" <?= $ocorrencia['naturalidade_distrito'] == 'Homoíne' ? 'selected' : '' ?>>Homoíne</option>
                  <option value="Inharrime" <?= $ocorrencia['naturalidade_distrito'] == 'Inharrime' ? 'selected' : '' ?>>Inharrime</option>
                  <option value="Inhassoro" <?= $ocorrencia['naturalidade_distrito'] == 'Inhassoro' ? 'selected' : '' ?>>Inhassoro</option>
                  <option value="Jangamo" <?= $ocorrencia['naturalidade_distrito'] == 'Jangamo' ? 'selected' : '' ?>>Jangamo</option>
                  <option value="Massinga" <?= $ocorrencia['naturalidade_distrito'] == 'Massinga' ? 'selected' : '' ?>>Massinga</option>
                  <option value="Morrumbene" <?= $ocorrencia['naturalidade_distrito'] == 'Morrumbene' ? 'selected' : '' ?>>Morrumbene</option>
                  <option value="Panda" <?= $ocorrencia['naturalidade_distrito'] == 'Panda' ? 'selected' : '' ?>>Panda</option>
                  <option value="Vilankulo" <?= $ocorrencia['naturalidade_distrito'] == 'Vilankulo' ? 'selected' : '' ?>>Vilankulo</option>
                  <option value="Zavala" <?= $ocorrencia['naturalidade_distrito'] == 'Zavala' ? 'selected' : '' ?>>Zavala</option>
                  <option value="Inhambane" <?= $ocorrencia['naturalidade_distrito'] == 'Inhambane' ? 'selected' : '' ?>>Inhambane (Cidade)</option>
                </optgroup>

                <optgroup label="Sofala">
                  <option value="Beira" <?= $ocorrencia['naturalidade_distrito'] == 'Beira' ? 'selected' : '' ?>>Beira (Cidade)</option>
                  <option value="Búzi" <?= $ocorrencia['naturalidade_distrito'] == 'Búzi' ? 'selected' : '' ?>>Búzi</option>
                  <option value="Caia" <?= $ocorrencia['naturalidade_distrito'] == 'Caia' ? 'selected' : '' ?>>Caia</option>
                  <option value="Chemba" <?= $ocorrencia['naturalidade_distrito'] == 'Chemba' ? 'selected' : '' ?>>Chemba</option>
                  <option value="Cheringoma" <?= $ocorrencia['naturalidade_distrito'] == 'Cheringoma' ? 'selected' : '' ?>>Cheringoma</option>
                  <option value="Chibabava" <?= $ocorrencia['naturalidade_distrito'] == 'Chibabava' ? 'selected' : '' ?>>Chibabava</option>
                  <option value="Dondo" <?= $ocorrencia['naturalidade_distrito'] == 'Dondo' ? 'selected' : '' ?>>Dondo</option>
                  <option value="Gorongosa" <?= $ocorrencia['naturalidade_distrito'] == 'Gorongosa' ? 'selected' : '' ?>>Gorongosa</option>
                  <option value="Machanga" <?= $ocorrencia['naturalidade_distrito'] == 'Machanga' ? 'selected' : '' ?>>Machanga</option>
                  <option value="Marínguè" <?= $ocorrencia['naturalidade_distrito'] == 'Marínguè' ? 'selected' : '' ?>>Marínguè</option>
                  <option value="Marromeu" <?= $ocorrencia['naturalidade_distrito'] == 'Marromeu' ? 'selected' : '' ?>>Marromeu</option>
                  <option value="Muanza" <?= $ocorrencia['naturalidade_distrito'] == 'Muanza' ? 'selected' : '' ?>>Muanza</option>
                  <option value="Nhamatanda" <?= $ocorrencia['naturalidade_distrito'] == 'Nhamatanda' ? 'selected' : '' ?>>Nhamatanda</option>
                </optgroup>

                <optgroup label="Manica">
                  <option value="Barué" <?= $ocorrencia['naturalidade_distrito'] == 'Barué' ? 'selected' : '' ?>>Barué</option>
                  <option value="Chimoio" <?= $ocorrencia['naturalidade_distrito'] == 'Chimoio' ? 'selected' : '' ?>>Chimoio (Cidade)</option>
                  <option value="Gondola" <?= $ocorrencia['naturalidade_distrito'] == 'Gondola' ? 'selected' : '' ?>>Gondola</option>
                  <option value="Guro" <?= $ocorrencia['naturalidade_distrito'] == 'Guro' ? 'selected' : '' ?>>Guro</option>
                  <option value="Macate" <?= $ocorrencia['naturalidade_distrito'] == 'Macate' ? 'selected' : '' ?>>Macate</option>
                  <option value="Machaze" <?= $ocorrencia['naturalidade_distrito'] == 'Machaze' ? 'selected' : '' ?>>Machaze</option>
                  <option value="Macossa" <?= $ocorrencia['naturalidade_distrito'] == 'Macossa' ? 'selected' : '' ?>>Macossa</option>
                  <option value="Manica" <?= $ocorrencia['naturalidade_distrito'] == 'Manica' ? 'selected' : '' ?>>Manica</option>
                  <option value="Mossurize" <?= $ocorrencia['naturalidade_distrito'] == 'Mossurize' ? 'selected' : '' ?>>Mossurize</option>
                  <option value="Sussundenga" <?= $ocorrencia['naturalidade_distrito'] == 'Sussundenga' ? 'selected' : '' ?>>Sussundenga</option>
                  <option value="Tambara" <?= $ocorrencia['naturalidade_distrito'] == 'Tambara' ? 'selected' : '' ?>>Tambara</option>
                  <option value="Vanduzi" <?= $ocorrencia['naturalidade_distrito'] == 'Vanduzi' ? 'selected' : '' ?>>Vanduzi</option>
                </optgroup>

                <optgroup label="Nampula">
                  <option value="Angoche" <?= $ocorrencia['naturalidade_distrito'] == 'Angoche' ? 'selected' : '' ?>>Angoche</option>
                  <option value="Eráti" <?= $ocorrencia['naturalidade_distrito'] == 'Eráti' ? 'selected' : '' ?>>Eráti</option>
                  <option value="Ilha de Moçambique" <?= $ocorrencia['naturalidade_distrito'] == 'Ilha de Moçambique' ? 'selected' : '' ?>>Ilha de Moçambique</option>
                  <option value="Lalaua" <?= $ocorrencia['naturalidade_distrito'] == 'Lalaua' ? 'selected' : '' ?>>Lalaua</option>
                  <option value="Malema" <?= $ocorrencia['naturalidade_distrito'] == 'Malema' ? 'selected' : '' ?>>Malema</option>
                  <option value="Meconta" <?= $ocorrencia['naturalidade_distrito'] == 'Meconta' ? 'selected' : '' ?>>Meconta</option>
                  <option value="Mecubúri" <?= $ocorrencia['naturalidade_distrito'] == 'Mecubúri' ? 'selected' : '' ?>>Mecubúri</option>
                  <option value="Memba" <?= $ocorrencia['naturalidade_distrito'] == 'Memba' ? 'selected' : '' ?>>Memba</option>
                  <option value="Mogincual" <?= $ocorrencia['naturalidade_distrito'] == 'Mogincual' ? 'selected' : '' ?>>Mogincual</option>
                  <option value="Mogovolas" <?= $ocorrencia['naturalidade_distrito'] == 'Mogovolas' ? 'selected' : '' ?>>Mogovolas</option>
                  <option value="Moma" <?= $ocorrencia['naturalidade_distrito'] == 'Moma' ? 'selected' : '' ?>>Moma</option>
                  <option value="Monapo" <?= $ocorrencia['naturalidade_distrito'] == 'Monapo' ? 'selected' : '' ?>>Monapo</option>
                  <option value="Mossuril" <?= $ocorrencia['naturalidade_distrito'] == 'Mossuril' ? 'selected' : '' ?>>Mossuril</option>
                  <option value="Nacala-a-Velha" <?= $ocorrencia['naturalidade_distrito'] == 'Nacala-a-Velha' ? 'selected' : '' ?>>Nacala-a-Velha</option>
                  <option value="Nacala-Porto" <?= $ocorrencia['naturalidade_distrito'] == 'Nacala-Porto' ? 'selected' : '' ?>>Nacala-Porto (Cidade)</option>
                  <option value="Nampula" <?= $ocorrencia['naturalidade_distrito'] == 'Nampula' ? 'selected' : '' ?>>Nampula (Cidade)</option>
                  <option value="Rapale" <?= $ocorrencia['naturalidade_distrito'] == 'Rapale' ? 'selected' : '' ?>>Rapale</option>
                  <option value="Ribáuè" <?= $ocorrencia['naturalidade_distrito'] == 'Ribáuè' ? 'selected' : '' ?>>Ribáuè</option>
                </optgroup>

                <optgroup label="Niassa">
                  <option value="Cuamba" <?= $ocorrencia['naturalidade_distrito'] == 'Cuamba' ? 'selected' : '' ?>>Cuamba</option>
                  <option value="Lago" <?= $ocorrencia['naturalidade_distrito'] == 'Lago' ? 'selected' : '' ?>>Lago</option>
                  <option value="Lichinga" <?= $ocorrencia['naturalidade_distrito'] == 'Lichinga' ? 'selected' : '' ?>>Lichinga (Cidade)</option>
                  <option value="Majune" <?= $ocorrencia['naturalidade_distrito'] == 'Majune' ? 'selected' : '' ?>>Majune</option>
                  <option value="Mandimba" <?= $ocorrencia['naturalidade_distrito'] == 'Mandimba' ? 'selected' : '' ?>>Mandimba</option>
                  <option value="Marrupa" <?= $ocorrencia['naturalidade_distrito'] == 'Marrupa' ? 'selected' : '' ?>>Marrupa</option>
                  <option value="Maúa" <?= $ocorrencia['naturalidade_distrito'] == 'Maúa' ? 'selected' : '' ?>>Maúa</option>
                  <option value="Mavago" <?= $ocorrencia['naturalidade_distrito'] == 'Mavago' ? 'selected' : '' ?>>Mavago</option>
                  <option value="Mecanhelas" <?= $ocorrencia['naturalidade_distrito'] == 'Mecanhelas' ? 'selected' : '' ?>>Mecanhelas</option>
                  <option value="Metarica" <?= $ocorrencia['naturalidade_distrito'] == 'Metarica' ? 'selected' : '' ?>>Metarica</option>
                  <option value="Muembe" <?= $ocorrencia['naturalidade_distrito'] == 'Muembe' ? 'selected' : '' ?>>Muembe</option>
                  <option value="N'gauma" <?= $ocorrencia['naturalidade_distrito'] == "N'gauma" ? 'selected' : '' ?>>N'gauma</option>
                  <option value="Sanga" <?= $ocorrencia['naturalidade_distrito'] == 'Sanga' ? 'selected' : '' ?>>Sanga</option>
                </optgroup>

                <optgroup label="Tete">
                  <option value="Angónia" <?= $ocorrencia['naturalidade_distrito'] == 'Angónia' ? 'selected' : '' ?>>Angónia</option>
                  <option value="Cahora-Bassa" <?= $ocorrencia['naturalidade_distrito'] == 'Cahora-Bassa' ? 'selected' : '' ?>>Cahora-Bassa</option>
                  <option value="Changara" <?= $ocorrencia['naturalidade_distrito'] == 'Changara' ? 'selected' : '' ?>>Changara</option>
                  <option value="Chifunde" <?= $ocorrencia['naturalidade_distrito'] == 'Chifunde' ? 'selected' : '' ?>>Chifunde</option>
                  <option value="Chiuta" <?= $ocorrencia['naturalidade_distrito'] == 'Chiuta' ? 'selected' : '' ?>>Chiuta</option>
                  <option value="Doa" <?= $ocorrencia['naturalidade_distrito'] == 'Doa' ? 'selected' : '' ?>>Doa</option>
                  <option value="Macanga" <?= $ocorrencia['naturalidade_distrito'] == 'Macanga' ? 'selected' : '' ?>>Macanga</option>
                  <option value="Magoe" <?= $ocorrencia['naturalidade_distrito'] == 'Magoe' ? 'selected' : '' ?>>Magoe</option>
                  <option value="Marávia" <?= $ocorrencia['naturalidade_distrito'] == 'Marávia' ? 'selected' : '' ?>>Marávia</option>
                  <option value="Moatize" <?= $ocorrencia['naturalidade_distrito'] == 'Moatize' ? 'selected' : '' ?>>Moatize</option>
                  <option value="Mutarara" <?= $ocorrencia['naturalidade_distrito'] == 'Mutarara' ? 'selected' : '' ?>>Mutarara</option>
                  <option value="Tete" <?= $ocorrencia['naturalidade_distrito'] == 'Tete' ? 'selected' : '' ?>>Tete (Cidade)</option>
                  <option value="Zumbu" <?= $ocorrencia['naturalidade_distrito'] == 'Zumbu' ? 'selected' : '' ?>>Zumbu</option>
                </optgroup>

                <optgroup label="Zambézia">
                  <option value="Alto Molócuè" <?= $ocorrencia['naturalidade_distrito'] == 'Alto Molócuè' ? 'selected' : '' ?>>Alto Molócuè</option>
                  <option value="Chinde" <?= $ocorrencia['naturalidade_distrito'] == 'Chinde' ? 'selected' : '' ?>>Chinde</option>
                  <option value="Gilé" <?= $ocorrencia['naturalidade_distrito'] == 'Gilé' ? 'selected' : '' ?>>Gilé</option>
                  <option value="Guruè" <?= $ocorrencia['naturalidade_distrito'] == 'Guruè' ? 'selected' : '' ?>>Guruè</option>
                  <option value="Ile" <?= $ocorrencia['naturalidade_distrito'] == 'Ile' ? 'selected' : '' ?>>Ile</option>
                  <option value="Inhassunge" <?= $ocorrencia['naturalidade_distrito'] == 'Inhassunge' ? 'selected' : '' ?>>Inhassunge</option>
                  <option value="Luabo" <?= $ocorrencia['naturalidade_distrito'] == 'Luabo' ? 'selected' : '' ?>>Luabo</option>
                  <option value="Lugela" <?= $ocorrencia['naturalidade_distrito'] == 'Lugela' ? 'selected' : '' ?>>Lugela</option>
                  <option value="Maganja da Costa" <?= $ocorrencia['naturalidade_distrito'] == 'Maganja da Costa' ? 'selected' : '' ?>>Maganja da Costa</option>
                  <option value="Milange" <?= $ocorrencia['naturalidade_distrito'] == 'Milange' ? 'selected' : '' ?>>Milange</option>
                  <option value="Mocuba" <?= $ocorrencia['naturalidade_distrito'] == 'Mocuba' ? 'selected' : '' ?>>Mocuba</option>
                  <option value="Mopeia" <?= $ocorrencia['naturalidade_distrito'] == 'Mopeia' ? 'selected' : '' ?>>Mopeia</option>
                  <option value="Namacurra" <?= $ocorrencia['naturalidade_distrito'] == 'Namacurra' ? 'selected' : '' ?>>Namacurra</option>
                  <option value="Quelimane" <?= $ocorrencia['naturalidade_distrito'] == 'Quelimane' ? 'selected' : '' ?>>Quelimane (Cidade)</option>
                </optgroup>

                <optgroup label="Cabo Delgado">
                  <option value="Ancuabe" <?= $ocorrencia['naturalidade_distrito'] == 'Ancuabe' ? 'selected' : '' ?>>Ancuabe</option>
                  <option value="Balama" <?= $ocorrencia['naturalidade_distrito'] == 'Balama' ? 'selected' : '' ?>>Balama</option>
                  <option value="Chiúre" <?= $ocorrencia['naturalidade_distrito'] == 'Chiúre' ? 'selected' : '' ?>>Chiúre</option>
                  <option value="Ibo" <?= $ocorrencia['naturalidade_distrito'] == 'Ibo' ? 'selected' : '' ?>>Ibo</option>
                  <option value="Macomia" <?= $ocorrencia['naturalidade_distrito'] == 'Macomia' ? 'selected' : '' ?>>Macomia</option>
                  <option value="Metuge" <?= $ocorrencia['naturalidade_distrito'] == 'Metuge' ? 'selected' : '' ?>>Metuge</option>
                  <option value="Mocímboa da Praia" <?= $ocorrencia['naturalidade_distrito'] == 'Mocímboa da Praia' ? 'selected' : '' ?>>Mocímboa da Praia</option>
                  <option value="Montepuez" <?= $ocorrencia['naturalidade_distrito'] == 'Montepuez' ? 'selected' : '' ?>>Montepuez</option>
                  <option value="Mueda" <?= $ocorrencia['naturalidade_distrito'] == 'Mueda' ? 'selected' : '' ?>>Mueda</option>
                  <option value="Namuno" <?= $ocorrencia['naturalidade_distrito'] == 'Namuno' ? 'selected' : '' ?>>Namuno</option>
                  <option value="Pemba" <?= $ocorrencia['naturalidade_distrito'] == 'Pemba' ? 'selected' : '' ?>>Pemba (Cidade)</option>
                  <option value="Quissanga" <?= $ocorrencia['naturalidade_distrito'] == 'Quissanga' ? 'selected' : '' ?>>Quissanga</option>
                </optgroup>

                <optgroup label="Internacional">
                  <option value="Outro" <?= $ocorrencia['naturalidade_distrito'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="container-5">
            <div class="d9">
              <label for="provincia">Província:</label>
              <select id="provincia" name="provincia" readonly>
                <option value="" disabled <?= empty($ocorrencia['provincia']) ? 'selected' : '' ?> hidden>Seleccione a província</option>
                <option value="Inhambane" <?= $ocorrencia['provincia'] == 'Inhambane' ? 'selected' : '' ?>>Inhambane</option>
                <option value="Maputo" <?= $ocorrencia['provincia'] == 'Maputo' ? 'selected' : '' ?>>Maputo</option>
                <option value="Gaza" <?= $ocorrencia['provincia'] == 'Gaza' ? 'selected' : '' ?>>Gaza</option>
                <option value="Sofala" <?= $ocorrencia['provincia'] == 'Sofala' ? 'selected' : '' ?>>Sofala</option>
                <option value="Manica" <?= $ocorrencia['provincia'] == 'Manica' ? 'selected' : '' ?>>Manica</option>
                <option value="Zambezia" <?= $ocorrencia['provincia'] == 'Zambezia' ? 'selected' : '' ?>>Zambezia</option>
                <option value="Tete" <?= $ocorrencia['provincia'] == 'Tete' ? 'selected' : '' ?>>Tete</option>
                <option value="Nampula" <?= $ocorrencia['provincia'] == 'Nampula' ? 'selected' : '' ?>>Nampula</option>
                <option value="Niassa" <?= $ocorrencia['provincia'] == 'Niassa' ? 'selected' : '' ?>>Niassa</option>
                <option value="Cabo-Delgado" <?= $ocorrencia['provincia'] == 'Cabo-Delgado' ? 'selected' : '' ?>>Cabo-Delgado</option>
                <option value="Outro" <?= $ocorrencia['provincia'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
              </select>
            </div>

            <div class="d10">
              <label for="nacionalidade">Nacionalidade:</label>
              <select id="nacionalidade" name="nacionalidade" readonly>
                <option value="" disabled <?= empty($ocorrencia['nacionalidade']) ? 'selected' : '' ?> hidden>Seleccione a nacionalidade</option>
                <option value="Moçambicana" <?= $ocorrencia['nacionalidade'] == 'Moçambicana' ? 'selected' : '' ?>>Moçambicana</option>
                <option value="Sul-Africana" <?= $ocorrencia['nacionalidade'] == 'Sul-Africana' ? 'selected' : '' ?>>Sul-Africana</option>
                <option value="Zimbabueana" <?= $ocorrencia['nacionalidade'] == 'Zimbabueana' ? 'selected' : '' ?>>Zimbabueana</option>
                <option value="Angolana" <?= $ocorrencia['nacionalidade'] == 'Angolana' ? 'selected' : '' ?>>Angolana</option>
                <option value="Brasileira" <?= $ocorrencia['nacionalidade'] == 'Brasileira' ? 'selected' : '' ?>>Brasileira</option>
                <option value="Chinesa" <?= $ocorrencia['nacionalidade'] == 'Chinesa' ? 'selected' : '' ?>>Chinesa</option>
                <option value="Indiana" <?= $ocorrencia['nacionalidade'] == 'Indiana' ? 'selected' : '' ?>>Indiana</option>
                <option value="Tailandesa" <?= $ocorrencia['nacionalidade'] == 'Tailandesa' ? 'selected' : '' ?>>Tailandesa</option>
                <option value="Suazi" <?= $ocorrencia['nacionalidade'] == 'Suazi' ? 'selected' : '' ?>>Suazi (ou eSwatini)</option>
                <option value="Outro" <?= $ocorrencia['nacionalidade'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
              </select>
            </div>
          </div>

            <div class="container-6">
              <div class="d11">
                <label for="local_trabalho">Endereço do trabalho:</label>
                <input type="text" id="local_trabalho" name="local_trabalho" value="<?= $ocorrencia['local_trabalho'] ?>" placeholder="Digite o endereço do local">
              </div>

              <div class="d12">
                <label for="bairro">Residente do bairro:</label>
                  <select id="bairro" name="bairro" required onchange="preencherChefeBairro()">
                  <option disabled hidden <?= empty($ocorrencia['bairro']) ? 'selected' : '' ?>>Seleccione o bairro</option>
                  <?php
                  include '../includes/conexao.php';

                  $sql = "SELECT * FROM tbl_chefe_bairro";
                  $stmt = $conn->query($sql);

                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $selected = ($ocorrencia['bairro'] == $row['bairro']) ? 'selected' : '';
                      echo '<option value="' . htmlspecialchars($row['bairro']) . '" data-chefe="' . htmlspecialchars($row['nome_completo']) . '" data-contato="' . htmlspecialchars($row['contacto']) . '" ' . $selected . '>' . htmlspecialchars($row['bairro']) . '</option>';
                  }

                  $conn = null;
                  ?>
                  </select>
              </div>

                <div class="">
                  <label for="chefe_bairro" hidden>Chefe do bairro:</label>
                  <input type="hidden" id="chefe_bairro" name="chefe_bairro" value="<?= $ocorrencia['chefe_bairro'] ?>">
                </div>

                <div class="">
                  <label for="contacto_chefe_bairro" hidden>Contacto do chefe do bairro:</label>
                  <input type="hidden" id="contacto_chefe_bairro" name="contacto_chefe_bairro" value="<?= $ocorrencia['contacto_chefe_bairro'] ?>">
                </div>
              </div>

          <div class="container-7">
            <div class="d13">
                <label for="contacto_cidadao">Contacto do cidadão:</label>
                <input type="number" id="contacto_cidadao" name="contacto_cidadao" value="<?= $ocorrencia['contacto_cidadao'] ?>" required placeholder="Digite o contacto">
            </div>

              <div class="d14">
              <label for="classificacao">Classificação:</label>
              <select id="classificacao" name="classificacao" required>
                  <option disabled hidden <?= empty($ocorrencia['classificacao']) ? 'selected' : '' ?>>Seleccione o tipo de ocorrência:</option>
                  <option value="Violência" <?= ($ocorrencia['classificacao'] === 'Violência') ? 'selected' : '' ?>>Violência</option>
                  <option value="Agressão" <?= ($ocorrencia['classificacao'] === 'Agressão') ? 'selected' : '' ?>>Agressão</option>
                  <option value="Roubo" <?= ($ocorrencia['classificacao'] === 'Roubo') ? 'selected' : '' ?>>Roubo</option>
                  <option value="Acidente" <?= ($ocorrencia['classificacao'] === 'Acidente') ? 'selected' : '' ?>>Acidente</option>
                  <option value="Outro" <?= ($ocorrencia['classificacao'] === 'Outro') ? 'selected' : '' ?>>Outro</option>
              </select>
            </div>
          </div>
        </div>

        <div class="div-2">

          <h3>(Características do caso)</h3>

          <div class="container-8">
            <div class="d15">
              <label for="data_ocorrido">Data do ocorrido:</label>
              <input type="date" id="data_ocorrido" name="data_ocorrido" value="<?= $ocorrencia['data_ocorrido'] ?>">
            </div>

            <div class="d16">
              <label for="hora_ocorrido">Hora do ocorrido</label>
              <input type="time" id="hora_ocorrido" name="hora_ocorrido" value="<?= $ocorrencia['hora_ocorrido'] ?>">
            </div>
          </div>

          <div class="container-9">
            <div class="d17">
              <label for="lugar_ocorrido">Lugar do ocorrido:</label>
              <input type="text" id="lugar_ocorrido" name="lugar_ocorrido" required placeholder="Digite o lugar da ocorrência" value="<?= $ocorrencia['lugar_ocorrido'] ?>">
            </div>

            <div class="d18">
              <label for="endereco_caso">Endereço do caso:</label>
              <input type="text" id="endereco_caso" name="endereco_caso" placeholder="Digite o endereço do caso" value="<?= $ocorrencia['endereco_caso'] ?>">
            </div>
          </div>

          <div class="container-10">
            <div class="d19">
              <label for="visado">Nome do visado:</label>
              <input type="number" id="contacto_visado" name="contacto_visado" placeholder="Digite o contacto" value="<?= $ocorrencia['contacto_visado'] ?>">
            </div>

            <div class="d20">
              <label for="contacto_visado">Contacto do visado:</label>
              <input type="text" id="nome_visado" name="nome_visado" placeholder="Digite o nome e o apelido" value="<?= $ocorrencia['nome_visado'] ?>">
            </div>
          </div>

          <div class="d19">
            <label for="descricao">Descrição do ocorrido:</label>
            <textarea id="descricao_ocorrido" name="descricao_ocorrido" placeholder="Digite aqui..." rows="4" required><?= $ocorrencia['descricao_ocorrido'] ?></textarea>
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
              <button class="submit-button" type="submit">Actualizar</button>
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
  document.getElementById('voltar-link').addEventListener('click', function() {
    window.history.back();
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
              "Massinga": "Inhambane", "Morrumbene": "Inhambane", "Maxixe": "Inhambane",
              "Panda": "Inhambane", "Vilankulo": "Inhambane", "Zavala": "Inhambane",
              "Inhambane": "Inhambane",

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
              "Mandimba": "Niassa", "Marrupa": "Niassa", "Maúa": "Niassa", "Mavago": "Niassa",
              "Mecanhelas": "Niassa", "Metarica": "Niassa", "Muembe": "Niassa", "N'gauma": "Niassa",
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
  /* ...existing code... */

  @media screen and (max-width: 570px) {
    body {
      background-image: url(/image/PRMmobile.jpeg);
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      min-height: 100vh !important;
      height: auto !important;
      overflow-y: auto !important;
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
