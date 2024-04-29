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

function obtenerComentarios($connection, $postId) {
    $comments = [];
    $stmt = $connection->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY id DESC");
    if ($stmt) {
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        $stmt->close();
    } else {
        error_log("Error al preparar la consulta: " . $connection->error);
    }
    return $comments;
}

$connection = conectarDB();
if ($connection) {
    $result = $connection->query("SELECT posts.*, usuarios.`Nombre Completo` FROM posts INNER JOIN usuarios ON posts.user_id = usuarios.Identificador WHERE posts.status = TRUE ORDER BY posts.id DESC");
    while ($post = $result->fetch_assoc()) {
        echo "<div class='post' data-post-id='" . $post['id'] . "'>";
        // Nombre del usuario
        echo "<h3>" . htmlspecialchars($post['Nombre Completo'], ENT_QUOTES, 'UTF-8') . "</h3>";
        // Fecha de publicación
        echo "<p>Publicado el: " . htmlspecialchars($post['timestamp'], ENT_QUOTES, 'UTF-8') . "</p>";
        // Contenido del post
        echo "<p>" . htmlspecialchars($post['text'], ENT_QUOTES, 'UTF-8') . "</p>";
        // Imagen del post si existe
        if ($post['image'] != 'Sin imagen') {
            $imagePath = '/Blog_California/images/' . basename($post['image']);
            echo "<img src='" . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . "' alt='Imagen del post' style='max-width: 100%; height: auto;' />";
        }
        // Botón de like para cada post
        echo "<div id='like'>";
        echo "<button class='like-button' data-post-id='" . $post['id'] . "'>Me Gusta <span class='like-count'>" . $post['likes'] . "</span></button>"; // Mostrar el número de likes
        echo "</div>";
        // Botón de eliminación para cada post
        echo "<button class='delete-post-btn' data-post-id='" . $post['id'] . "'>Eliminar Post</button>";

        // Formulario de comentarios para cada post
        echo "<div id='comments'>";
        echo "<form action='PHP/comment.php' method='post'>";
        echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";
        echo "<input type='text' name='comment' placeholder='Escribe un comentario...'>";
        echo "<input type='submit' value='Comentar'>";
        echo "</form>";
        // Mostrar los comentarios de cada post
        $comments = obtenerComentarios($connection, $post['id']);
        foreach ($comments as $comment) {
            echo "<div class='comment'>";
            echo "<p>" . htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";
        }
        echo "</div>"; // Cierre del div de comentarios
        echo "</div>"; // Cierre del div del post
    }
    $connection->close();
} else {
    echo "Error al conectar con la base de datos.";
}
?>
