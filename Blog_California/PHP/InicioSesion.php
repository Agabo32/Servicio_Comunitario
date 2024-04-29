
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <link rel="stylesheet" href="../CSS/Estilo_InicioSesion.css">
    
</head>


<body>
    
<main>
    <div class = "Contenedor_Inicio">

        <div class = "Caja_Trasera">
           <div class = "Caja_Inicio">
              <h3>¡Bienvenido!</h3>
              <p>Inicia sesión para entrar a la página</p>
           </div>
        </div>

        <!--Inicio de sesión-->
        <div class = "Contenedor_Delantero">
            <form action="InicioSesion_BD.php" method="POST" class = "Formulario_Inicio">
                <h2>Iniciar Sesión</h2>
                <input type="text" placeholder="Usuario" name ="Usuario">
                <input type="password" placeholder="Contraseña" name="Contrasena">
                <button>Entrar</button>
            </form>
        </div>
        
    </div>
</main>

</body>
</html>