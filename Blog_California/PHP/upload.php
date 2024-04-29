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
    if (isset($_POST['text'])) {
        $text = $_POST['text'];
        $targetDir = 'C:/xampp/htdocs/Blog_California/images/';
        $fileExtension = '';
        $targetFile = '';

        // Verificar si se ha subido un archivo
        if (isset($_FILES['img']) && $_FILES['img']['error'] != UPLOAD_ERR_NO_FILE) {
            $fileExtension = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                exit("Tipo de archivo no permitido.");
            }

            if ($_FILES["img"]["size"] > $maxFileSize) {
                exit("El archivo es demasiado grande.");
            }

            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $uniqueFileName = uniqid() . '.' . $fileExtension;
            $targetFile = $targetDir . $uniqueFileName;

            if ($_FILES["img"]["error"] === 0 && move_uploaded_file($_FILES["img"]["tmp_name"], $targetFile)) {
                echo 'Archivo movido exitosamente: ' . $targetFile;
            } else {
                exit("Error al mover el archivo subido.");
            }
        } else {
            $targetFile = 'Sin imagen';
        }

        $connection = conectarDB();
        if ($connection) {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['Usuario'])) {
                echo "Error: Usuario no autenticado.";
                exit;
            }

            // Obtener el user_id del usuario autenticado
            $user = $_SESSION['Usuario'];
            $stmt = $connection->prepare("SELECT Identificador FROM usuarios WHERE Usuario = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['Identificador'];
            } else {
                echo "Error: Usuario no encontrado.";
                exit;
            }
            $stmt->close();

            // Preparar la consulta para insertar el post en la base de datos
            $stmt = $connection->prepare("INSERT INTO posts (text, image, user_id) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssi", $text, $targetFile, $user_id);
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
}
?>

}
?>
