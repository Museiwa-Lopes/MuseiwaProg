<?php
    $timezone = new DateTimeZone('Africa/Harare'); // Fuso horário de Harare
    $data_actual = (new DateTime())->setTimezone($timezone)->format('d/m/Y');
    $hora_actual = (new DateTime())->setTimezone($timezone)->format('H:i');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; }
        .linha { margin-bottom: 8px; }
        .campo { border-bottom: 1px solid #000; display: inline-block; min-width: 150px; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">
        <img src="../image/emblema_mocambique.png" style="width: 100px; display: block; margin: 0 auto 10px;">
        <br>REPÚBLICA DE MOÇAMBIQUE<br>MINISTÉRIO DO INTERIOR<br>COMANDO DISTRITAL DA PRM-VILANKULO
    </h3>

    <p style="text-align: center;" class="linha">Auto de denúncia n°______, Unidade:____________, Distrito: <strong>Vilankulo</strong></p>
    <p style="text-align: center;" class="linha">Província de <strong><?= $provincia ?></strong>, Zona:__________, Data: <strong><?= $data_actual ?></strong>, Horas: <strong><?= $hora_actual ?>min</strong></p>
    <br>
    <p class="linha">Classificação do Caso: <strong><?= $classificacao ?></strong></p>
    <p class="linha">Nesta Unidade, apresentou-se o(a) cidadão(ã): <strong><?= $nome ?></strong></p>
    <p class="linha">Sexo: <strong><?= $sexo ?></strong>, Estado civil: <strong><?= $estado_civil ?></strong>, com <strong><?= $idade ?></strong> anos de idade, nascido em <strong><?= $data_nascimento ?></strong></p>
    <p class="linha">Filho(a) de <strong><?= $pai ?></strong> e <strong><?= $mae ?></strong></p>
    <p class="linha">Natural de <strong><?= $naturalidade ?></strong>, Província: <strong><?= $provincia ?></strong>, Nacionalidade: <strong><?= $nacionalidade ?></strong></p>
    <p class="linha">Local de trabalho: <strong><?= $local_trabalho ?></strong></p>
    <p class="linha">Endereço do local de trabalho: <strong><?= $endereco_trabalho ?></strong></p>
    <p class="linha">É residente do Bairro: <strong><?= $bairro ?></strong></p>
    <p class="linha">Av./Rua: <strong><?= $endereco_caso ?></strong></p>
    <br>
    <p class="linha"><strong>Características do Caso:</strong></p>
    <p class="linha">Dia/Ocorreu: <strong><?= $data_ocorrido ?></strong> pelas <strong><?= $hora_ocorrido ?>min</strong></p>
    <p class="linha">Lugar: <strong><?= $lugar_ocorrido ?></strong></p>
    <br>
    <p><strong>Declara ou Denuncia:</strong></p>
    <p><?= nl2br(htmlspecialchars($descricao)) ?></p>
</body>
</html>