<?php

session_start();

  include 'Conexion_BD.php';

  $Usuario = $_POST['Usuario'];
  $Contrasena = $_POST['Contrasena'];

  $Validar_Inicio = mysqli_query($Conexion, "SELECT * FROM usuarios WHERE
  Usuario = '$Usuario' and Contrasena = '$Contrasena'");

  if(mysqli_num_rows($Validar_Inicio) > 0){
    $_SESSION['Usuario'] = $Usuario;
    header("location:../post.html");
    exit;
  }else{
    echo '
        <script>
        alert("El usuario no existe. Por favor verifique los datos ingresados.");
        window.location = "InicioSesion.php";
        </script>
    ';
    exit;
  }



?>
