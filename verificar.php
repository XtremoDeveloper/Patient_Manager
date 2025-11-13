<?php
session_start();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = trim($_POST["codigo"]);

    if ($codigo === "1803462293") {
        // Guardamos una variable de sesión para autorizar el registro
        $_SESSION["verificado"] = true;
        header("Location: registro_usuario.php");
        exit;
    } else {
        $mensaje = "❌ Código incorrecto. Inténtelo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Identidad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .verificacion-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
            width: 380px;
            text-align: center;
        }

        .verificacion-container h2 {
            margin-bottom: 20px;
            color: #00796b;
        }

        .verificacion-container label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #004d40;
        }

        .verificacion-container input {
            width: 100%;
            padding: 12px;
            border: 1px solid #b2dfdb;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .verificacion-container button {
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

        .verificacion-container button:hover {
            background: #004d40;
        }

        .mensaje-error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
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
    <div class="verificacion-container">
        <div class="icono">🔒</div>
        <h2>Verificar Identidad del Sistema</h2>

        <?php if(!empty($mensaje)) echo "<p class='mensaje-error'>$mensaje</p>"; ?>

        <form method="POST">
            <label>Código de acceso (10 dígitos):</label>
            <input type="text" name="codigo" maxlength="10" placeholder="Ingrese el código" required>
            <button type="submit">Verificar</button>
        </form>

        <a class="volver" href="login.php">⬅️ Volver al Inicio de Sesión</a>
    </div>
</body>
</html>
