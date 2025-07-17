<?php
include '../includes/conexao.php';

if (!isset($_GET['id'])) {
    echo "<p style='color: red;'>Contacto não especificado!</p>";
    exit;
}

$contacto_cidadao = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tbl_ocorrencia WHERE contacto_cidadao = ? ORDER BY data_ocorrido DESC, hora_ocorrido DESC");
$stmt->execute([$contacto_cidadao]);
$ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histórico de Ocorrências - PRM | MuseiwaProg</title>
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
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      color: #fff;
      background-color: #48ed;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
      border-radius: 30px;
      padding: 30px;
      position: relative;
      overflow: hidden;
      width: 90%;
      max-width: 1000px;
    }

    h2 {
      color: #000066;
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color:rgb(0, 0, 0);
      text-align: center;
    }

    /* tr:nth-child(even) {
      background-color:rgb(0, 0, 0);
    } */

    .actions a {
      margin-right: 8px;
      text-decoration: none;
      font-size: 18px;
    }

    .back-link {
      margin-top: 20px;
      display: inline-block;
      text-decoration: none;
      background-color: rgb(55, 142, 65);
      color: white;
      border: 2px solid rgb(255, 255, 255);
      padding: 10px 16px;
      border-radius: 8px;
      font-weight: 600;
    }

    .back-link:hover {
      background-color: rgba(11, 2, 53, 0.8);
      transition: 1s;
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
    padding: 5px !important;
  }
  .container {
    width: 98vw !important;
    min-width: unset !important;
    max-width: 99vw !important;
    padding: 10px !important;
    border-radius: 15px !important;
    margin: 10px auto !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    font-size: 15px !important;
  }
  h2 {
    font-size: 1.1em !important;
    word-break: break-word;
  }
  table {
    font-size: 13px !important;
    min-width: 600px;
  }
  th, td {
    padding: 6px !important;
    width: 30px !important;
    font-size: 13px !important;
    word-break: break-word;
  }
  .back-link {
    width: 100%;
    font-size: 16px !important;
    padding: 10px 0 !important;
    margin-top: 10px !important;
  }
  .actions img {
    width: 20px !important;
  }
  .container {
    overflow-x: auto !important;
  }
}
  </style>
</head>
<body>
  <div class="container">
    <!-- <h2>📄 Histórico de Ocorrências<br>Contacto: +258<?= htmlspecialchars($contacto_cidadao) ?></h2> -->
    <h2>📄 Histórico de Ocorrências</h2>

    <?php if (count($ocorrencias) > 0): ?>

      <div style="overflow-x: auto;">
        <table>
        <thead>
          <tr>
            <th>Nome (Cidadão)</th>
            <th>Bairro (Residência)</th>
            <th>Data (Ocorrido)</th>
            <th>Hora (Ocorrido)</th>
            <th>Descrição (Ocorrido)</th>
            <th>Estado</th>
            <th>Acção</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ocorrencias as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['nome_cidadao']) ?></td>
              <td><?= htmlspecialchars($row['bairro']) ?></td>
              <td><?= htmlspecialchars($row['data_ocorrido']) ?></td>
              <td><?= htmlspecialchars(ucfirst($row['hora_ocorrido'])) ?></td>
              <td class="reduz"><?= htmlspecialchars($row['descricao_ocorrido']) ?></td>
              <td><?= htmlspecialchars(ucfirst($row['estado'])) ?></td>
              <td class="actions">
                <a href="visualizar_ocorrencia.php?id=<?= $row['id'] ?>" style="border: none; background: none; padding: 0;">
                  <img src="../image/visualizar.png" title="Visualizar" style="width: 23px;">
                </a>

                <a href="editar_ocorrencia.php?id=<?= $row['id'] ?>" style="border: none; background: none; padding: 0;">
                  <img src="../image/actualizar.png" title="Actualizar" style="width: 23px;">
                </a>

                <a href="excluir_ocorrencia.php?id=<?= $row['id'] ?>" style="border: none; background: none; padding: 0;" title="Excluir" onclick="return confirm('Deseja realmente excluir esta ocorrência?')">
                  <img src="../image/excluir.png" title="Excluir" style="width: 23px;">
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>


    <?php else: ?>
      <p>Nenhuma ocorrência encontrada para este Contacto.</p>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
    <a class="back-link" href="registar_ocorrencia.php">⬅️ Voltar</a>
    </div>
  </div>
</body>
</html>

<script>
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
