<?php
$servidor = 'localhost';
$banco = 'sigo';
$username = 'root';
$password = '';

// Estabeleça a conexão com o banco de dados usando PDO
try {
    $conn = new PDO("mysql:host=$servidor;dbname=$banco", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>