<?php
session_start();
include("conexion.php");

$codigo_correcto = "1803462293"; // Código especial para poder registrarse
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = trim($_POST["codigo"]);
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);
    $nombre = trim($_POST["nombre"]);
    $rol = $_POST["rol"];

    // Validación del código
    if ($codigo !== $codigo_correcto) {
        $mensaje = "❌ Código de registro inválido.";
    } else {
        // Validación de campos
        if (!empty($usuario) && !empty($password) && !empty($nombre)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (usuario, password, rol, nombre_completo)
                    VALUES ('$usuario', '$passwordHash', '$rol', '$nombre')";

            if (mysqli_query($conexion, $sql)) {
                $mensaje = "✅ Usuario registrado con éxito. Ahora puede iniciar sesión.";
            } else {
                $mensaje = "❌ Error al registrar usuario: " . mysqli_error($conexion);
            }
        } else {
            $mensaje = "⚠️ Todos los campos son obligatorios.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
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

        .registro-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
            width: 420px;
            text-align: center;
        }

        .registro-container h2 {
            margin-bottom: 20px;
            color: #00796b;
        }

        .registro-container label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #004d40;
        }

        .registro-container input,
        .registro-container select {
            width: 100%;
            padding: 12px;
            border: 1px solid #b2dfdb;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .registro-container button {
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

        .registro-container button:hover {
            background: #004d40;
        }

        .mensaje {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .mensaje.error {
            color: red;
        }

        .mensaje.exito {
            color: green;
        }

        .volver {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #0288d1;
            font-weight: bold;
        }

        .volver:hover {
            color: #01579b;
        }

        .icono {
            font-size: 45px;
            color: #00796b;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <div class="icono">📝</div>
        <h2>Registro de Usuario</h2>

        <?php if(!empty($mensaje)) {
            $clase = (strpos($mensaje, "✅") !== false) ? "exito" : "error";
            echo "<p class='mensaje $clase'>$mensaje</p>";
        } ?>

        <form method="POST">
            <label>Código de Registro (10 dígitos)</label>
            <input type="text" name="codigo" maxlength="10" required>

            <label>Nombre completo</label>
            <input type="text" name="nombre" required>

            <label>Usuario</label>
            <input type="text" name="usuario" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <label>Rol</label>
            <select name="rol" required>
                <option value="enfermero">Enfermero</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Administrador</option>
            </select>

            <button type="submit">Registrar</button>
        </form>

        <a class="volver" href="login.php">⬅️ Volver al Login</a>
    </div>
</body>
</html>
