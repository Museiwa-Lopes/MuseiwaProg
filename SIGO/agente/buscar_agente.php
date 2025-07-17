<?php 

include "../includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = isset($_POST['search_query']) ? "%" . $_POST['search_query'] . "%" : '%';
    $tipo_usuario = 'Agente';

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_usuarios WHERE tipo_usuario = ?");
        $stmt->execute(array($tipo_usuario));

        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("SELECT * FROM tbl_usuarios 
                                    WHERE tipo_usuario = ? 
                                    AND (nome LIKE ? OR apelido LIKE ? OR genero LIKE ? OR distintivo LIKE ?)");
            $stmt->execute(array($tipo_usuario, $search_query, $search_query, $search_query, $search_query));

            if ($stmt->rowCount() > 0) {
                echo '
                <table class="cheads-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Apelido</th>
                            <th>Género</th>
                            <th>N° de distintivo</th>
                        </tr>
                    </thead>
                    <tbody>';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                        <tr>
                            <td>' . htmlspecialchars($row['nome']) . '</td>
                            <td>' . htmlspecialchars($row['apelido']) . '</td>
                            <td>' . htmlspecialchars($row['genero']) . '</td>
                            <td>' . htmlspecialchars($row['distintivo']) . '</td>
                        </tr>';
                }

                echo '
                    </tbody>
                </table>';
            } else {
                echo 'Nenhum agente cadastrado.';
            }
        } else {
            echo 'Nenhum agente encontrado.';
        }
    } catch (PDOException $e) {
        echo 'Erro ao obter dados dos administradores: ' . $e->getMessage();
    }
}

?>
