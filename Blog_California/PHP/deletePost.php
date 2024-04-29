<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function conectarDB() {
    $connection = new mysqli('localhost', 'root', '', 'comunidad_california');
    if ($connection->connect_error) {
        error_log("Error de conexión: " . $connection->connect_error);
        return null;
    }
    return $connection;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    $connection = conectarDB();
    if ($connection) {
        $connection->begin_transaction();
        $stmt = $connection->prepare("UPDATE posts SET status = FALSE WHERE id = ?"); // Corregido a 'id'
        if ($stmt) {
            $stmt->bind_param("i", $postId);
            if ($stmt->execute()) {
                $connection->commit();
                echo 'success';
            } else {
                $connection->rollback();
                echo 'error';
            }
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $connection->error;
        }
        $connection->close();
    } else {
        echo "Error al conectar con la base de datos.";
    }
}
?>
