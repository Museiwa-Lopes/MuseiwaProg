<?php
// filepath: c:\Users\musei\OneDrive\Documentos\General Documents\Docs 2025\Minha Monografia\SIGO\admin\enviar_notificacao_semo.php

session_start();
require_once '../vendor/autoload.php';

// Função para formatar número moçambicano para +2588xxxxxxx
function formatarNumero($numero) {
    $numero = preg_replace('/\D/', '', $numero);
    if (preg_match('/^(8[2-7]\d{7})$/', $numero)) {
        return '+258' . $numero;
    }
    if (preg_match('/^(2588[2-7]\d{7})$/', $numero)) {
        return '+' . $numero;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = "ACe2f8926e5d1eeda8ca1624c1bb18f9ca";
    $token = "b7b1bc1defd7b95da5f1731e9a1c2f6c";
    $client = new Twilio\Rest\Client($sid, $token);

    $numeroVisado = formatarNumero($_POST['contacto_visado'] ?? '');
    $numeroChefe  = formatarNumero($_POST['contacto_chefe_bairro'] ?? '');
    $mensagemTexto = trim($_POST['mensagem'] ?? '');

    try {
        if ($numeroVisado) {
            $client->messages->create(
                $numeroVisado,
                [
                    'from' => '+15855222968',
                    'body' => $mensagemTexto
                ]
            );
        }
        if ($numeroChefe) {
            $client->messages->create(
                $numeroChefe,
                [
                    'from' => '+15855222968',
                    'body' => $mensagemTexto
                ]
            );
        }

        $_SESSION['mensagem']      = "✅ Mensagem enviada com sucesso para o visado e para o chefe do bairro.";
        $_SESSION['tipo_mensagem'] = "sucesso";
        header("Location: sucesso_mensagem_enviada.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['mensagem']      = "❌ Erro ao enviar SMS: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
        // header("Location: sucesso_mensagem_enviada.php");
        exit();
    }
}
?>
