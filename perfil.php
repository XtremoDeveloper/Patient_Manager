<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background: #00796b; /* Verde azulado */
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
        }

        header h2 {
            margin: 0;
            font-size: 28px;
        }

        .perfil-container {
            max-width: 500px;
            background: #ffffff;
            margin: 50px auto;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .perfil-container h3 {
            margin-bottom: 20px;
            color: #00796b;
        }

        .perfil-container p {
            font-size: 18px;
            margin: 10px 0;
            color: #444;
        }

        .perfil-container p span {
            font-weight: bold;
            color: #004d40;
        }

        a.logout {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #e53935;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        a.logout:hover {
            background: #c62828;
        }

        footer {
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            background: #004d40;
            color: white;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
        }
    </style>
</head>
<body>
    <header>
        <h2>👩‍⚕️ Panel de Enfermería</h2>
    </header>

    <div class="perfil-container">
        <h3>Bienvenido, <?php echo $_SESSION["nombre"]; ?> 👋</h3>
        <p><span>Usuario:</span> <?php echo $_SESSION["usuario"]; ?></p>
        <p><span>Rol:</span> <?php echo $_SESSION["rol"]; ?></p>
        <a class="logout" href="logout.php">Cerrar Sesión</a>
    </div>

    <footer>
        © 2025 Sistema de Enfermería | Cuidado y Salud
    </footer>
</body>
</html>
