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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_id'], $_POST['comment'])) {
        $postId = $_POST['post_id'];
        $comment = $_POST['comment'];

        $connection = conectarDB();
        if ($connection) {
            $stmt = $connection->prepare("INSERT INTO comments (post_id, comment) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("is", $postId, $comment);
                $stmt->execute();
                $stmt->close();
            } else {
                error_log("Error al preparar la consulta: " . $connection->error);
            }
            $connection->close();
        } else {
            echo "Error al conectar con la base de datos.";
        }
    }
    // Redirigir al usuario de vuelta a la página de la publicación
    header('Location: http://localhost/Blog_California/post.html');
    exit;
}

?>
