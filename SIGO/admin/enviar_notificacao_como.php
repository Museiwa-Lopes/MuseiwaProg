<?php
session_start();

require_once '../vendor/autoload.php';




/**
 * Formata número moçambicano para +2588xxxxxxx
 */
function formatarNumero($numero) {
    $numero = preg_replace('/\D/', '', $numero);
    if (preg_match('/^(8[2-7]\d{7})$/', $numero)) {
        return '+258' . $numero;
    }
    return false;
}

if (isset($_POST['notificar'])) {
    $sid = "ACe2f8926e5d1eeda8ca1624c1bb18f9ca"; // coloque o codigo id// Já está Eng.
    $token = "b7b1bc1defd7b95da5f1731e9a1c2f6c";
    $client = new Twilio\Rest\Client($sid, $token);


    // Formata os contactos
    $numeroVisado = formatarNumero($_POST['contacto_visado']);
    $numeroChefe  = formatarNumero($_POST['contacto_chefe_bairro']);
    $mensagemTexto = trim($_POST['mensagem'] ?? '');


    try {
        // Envia SMS em lote
        $destinatarios = [];
        if ($numeroVisado) $destinatarios[] = $numeroVisado;
        if ($numeroChefe) $destinatarios[] = $numeroChefe;

        foreach ($destinatarios as $numero) {
            $message = $client->messages->create(
                $numero,
                [
                    'from' => '+15855222968',
                    'body' => $mensagemTexto
                ]
            );
            file_put_contents('sms_debug.log', "Enviado para: $numero | Status: {$message->status} | SID: {$message->sid}\n", FILE_APPEND);
        }



        $_SESSION['mensagem']      = "✅ Mensagem enviada com sucesso para o visado e para o chefe do bairro.";
        $_SESSION['tipo_mensagem'] = "sucesso";
        header("Location: sucesso_mensagem_enviada_como.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['mensagem']      = "❌ Erro ao enviar SMS: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
        //header("Location: erro_envio.php");
        print_r( $e->getMessage());
        exit();
    }
}
