<?php
session_start();
include "../includes/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_query = isset($_POST['search_query']) ? "%" . $_POST['search_query'] . "%" : '%';
    $tipo_usuario = 'Admin'; // Procurar apenas utilizadores do tipo 'Admin'

    try {
        // Procurar administradores do tipo 'Admin'
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
                        <th>Acção</th>
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
                        <td>';

                // Apenas Admin_Master pode ver os botões de acção
                if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "Admin_Master") {
                    echo '
                            <form action="actualizar_admin.php" method="post">
                                <button class="update-button" name="editar" value="' . htmlspecialchars($row['distintivo']) . '">Actualizar</button>
                                <button class="delete-button" name="excluir" type="button" onclick="confirmDelete(' . htmlspecialchars($row['distintivo']) . ')">Excluir</button>
                            </form>';
                } else {
                    echo 'Sem permissão';
                }

                echo '</td>
                    </tr>';
            }

            echo '
                </tbody>
            </table>';
        } else {
            echo 'Nenhum administrador promovido.';
        }

    } catch (PDOException $e) {
        echo 'Erro ao obter dados dos administradores: ' . $e->getMessage();
    }
}
?>
