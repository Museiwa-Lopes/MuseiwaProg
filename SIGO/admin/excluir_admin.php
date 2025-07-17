<?php 

   include '../includes/conexao.php';

   if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["distintivo"])) {
     $adminId = $_GET["distintivo"];

     try{
        $stmt = $conn->prepare("DELETE FROM tbl_usuarios WHERE distintivo = ?");
        $stmt->execute([$adminId]);

        header("location: gerenciar_admin.php");
        exit();
     } catch(PDOException $e) {
        echo 'Erro ao excluir o administrador: ' . $e->getMessage();
     }

   }

  $conn = null;
?>