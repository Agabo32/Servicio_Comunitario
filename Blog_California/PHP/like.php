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

session_start(); // Iniciar la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['Usuario'])) {
            echo json_encode(['error' => 'Error: Usuario no autenticado.']);
            exit;
        }

        // Obtener el user_id del usuario autenticado
        $user = $_SESSION['Usuario'];
        $connection = conectarDB();
        if ($connection) {
            $stmt = $connection->prepare("SELECT Identificador FROM usuarios WHERE Usuario = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['Identificador'];
            } else {
                echo json_encode(['error' => 'Error: Usuario no encontrado.']);
                exit;
            }
            $stmt->close();

            // Verificar si ya existe un "like" de ese usuario para ese post
            $stmt = $connection->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $postId, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode(['error' => 'Ya has dado "like" a este post.']);
                exit;
            }
            $stmt->close();

            // Insertar el like en la tabla likes
            $stmt = $connection->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ii", $postId, $user_id);
                $stmt->execute();
                $stmt->close();
            } else {
                error_log("Error al preparar la consulta: " . $connection->error);
                echo json_encode(['error' => 'Error al preparar la consulta.']);
                exit;
            }

            // Actualizar el contador de likes en la tabla posts
            $stmt = $connection->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $postId);
                $stmt->execute();
                $stmt->close();
            } else {
                error_log("Error al preparar la consulta: " . $connection->error);
                echo json_encode(['error' => 'Error al preparar la consulta.']);
                exit;
            }

            $connection->close();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Error al conectar con la base de datos.']);
        }
    }
}
?>

