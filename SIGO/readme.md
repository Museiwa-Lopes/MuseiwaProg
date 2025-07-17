// Send an SMS using Twilio's REST API and PHP
<?php
// Required if your environment does not handle autoloading
require __DIR__ . '/vendor/autoload.php';

// Your Account SID and Auth Token from console.twilio.com
$sid = "ACe8defcd7dda34a4e004f7228a299a7fd";
$token = "963f2fab2b6d28fb479cf220310041fc";
$client = new Twilio\Rest\Client($sid, $token);

// Use the Client to make requests to the Twilio REST API
$client->messages->create(
    // The number you'd like to send the message to
    '+258848218024',
    [
        // A Twilio phone number you purchased at https://console.twilio.com
        'from' => '+19787594394',
        // The body of the text message you'd like to send
        'body' => "Está  sendo solicitado a comparecer na 2ª esquadra. Por favor, compareça o mais rápido possível."
    ]
);



///// pordes ficar a procurar e colocar a variavel.... nao tenho muito tempo.....Está bem Eng.

<?php
session_start();

require '../vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

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
    // Substitui pelos teus dados da Africa's Talking
    $username = 'sandbox';              // ou o teu username em produção
    $apiKey   = 'atsk_20f1f1749d2212847f79821245e93c1d3c301d02fc6ec34884a0b33726fad0f9e9ca96f7';

    // Formata os contactos
    $numeroVisado = formatarNumero($_POST['contacto_visado']);
    $numeroChefe  = formatarNumero($_POST['contacto_chefe_bairro']);
    $mensagemTexto = trim($_POST['mensagem'] ?? '');

    // Inicializar o SDK
    $AT  = new AfricasTalking($username, $apiKey);
    $sms = $AT->sms();

    $destinatarios = [$numeroVisado, $numeroChefe];
    try {
        // Envia SMS em lote
        $result = $sms->send([
            //'to'      => implode(',', $destinatarios),
            'to'      => '+258868675856',
            'message' => 'teste',
            'from'    => 'PRM' // Opcional: o teu sender ID aprovado
        ]);

        // Verifica cada resposta de envio
        foreach ($result['data'] as $envio) {
            if ($envio->status !== 'Success') {
                throw new Exception("Falha no envio para {$envio->number}: {$envio->status}");
            }
        }

        $_SESSION['mensagem']      = "✅ Mensagem enviada com sucesso para o visado e para o chefe do bairro.";
        $_SESSION['tipo_mensagem'] = "sucesso";
        header("Location: sucesso_mensagem_enviada.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['mensagem']      = "❌ Erro ao enviar SMS: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "erro";
        //header("Location: erro_envio.php");
        print_r( $e->getMessage());
        exit();
    }
}



