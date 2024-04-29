<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para conectar a la base de datos
function conectarDB() {
    $connection = new mysqli('localhost', 'root', '', 'comunidad_california');
    if ($connection->connect_error) {
        error_log("Error de conexión: " . $connection->connect_error);
        return null;
    }
    return $connection;
}

// Verifica si la solicitud es POST y la acción es 'deleteAll'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteAll') {
    $connection = conectarDB();
    if ($connection) {
        // Inicia una transacción para asegurar la integridad de la base de datos
        $connection->begin_transaction();
        $stmt = $connection->prepare("DELETE FROM posts");
        if ($stmt) {
            if ($stmt->execute()) {
                // Si la eliminación es exitosa, confirma la transacción
                $connection->commit();
                echo 'success';
            } else {
                // Si hay un error, revierte la transacción
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
