<?php
session_start();
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' LIMIT 1";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);

        if (password_verify($password, $row["password"])) {
            $_SESSION["usuario"] = $row["usuario"];
            $_SESSION["rol"] = $row["rol"];
            $_SESSION["nombre"] = $row["nombre_completo"];

            // Redirigir según el rol
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "❌ Contraseña incorrecta.";
        }
    } else {
        $mensaje = "❌ Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f2f1, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #00796b;
        }

        .login-container label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #004d40;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            border: 1px solid #b2dfdb;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .login-container button {
            width: 100%;
            background: #00796b;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-container button:hover {
            background: #004d40;
        }

        .login-container .register-btn {
            margin-top: 15px;
            background: #0288d1;
        }

        .login-container .register-btn:hover {
            background: #01579b;
        }

        .mensaje-error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .icono {
            font-size: 40px;
            color: #00796b;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="icono">👩‍⚕️</div>
        <h2>Iniciar Sesión</h2>

        <?php if(!empty($mensaje)) echo "<p class='mensaje-error'>$mensaje</p>"; ?>

        <form method="POST">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" required>

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Ingrese su contraseña" required>

            <button type="submit">Ingresar</button>
        </form>

        <form action="verificar.php" method="get">
            <button type="submit" class="register-btn">Registrarse</button>
        </form>
    </div>
</body>
</html>
