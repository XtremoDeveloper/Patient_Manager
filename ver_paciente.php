<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha del Paciente</title>
    <style>
        body { font-family: Arial; background: #fff; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ccc; }
        th { background-color: #f2f2f2; }
        .boton-editar {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .boton-editar:hover {
            background-color: #45a049;
        }
    </style>
    <?php
    include("conexion.php");

    $id = $_GET["id"];
    $result = $conexion->query("SELECT * FROM ficha_paciente WHERE id = $id");

    if ($result && $result->num_rows > 0) {
        $paciente = $result->fetch_assoc();
    } else {
        die("Paciente no encontrado");
    }
    ?>
</head>
<body>
    <h2>Ficha del Paciente</h2>
    
    <!-- Botón para ir a la página de edición -->
    <a href="editar_paciente.php?id=<?php echo $id; ?>" class="boton-editar">Editar</a>
    <br><br>

    <table>
        <?php foreach ($paciente as $campo => $valor): ?>
            <tr>
                <th><?php echo ucfirst(str_replace("_", " ", $campo)); ?></th>
                <td><?php echo $valor; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha del Paciente</title>
    <style>
        body { font-family: Arial; background: #fff; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ccc; }
        th { background-color: #f2f2f2; }
        .boton-editar {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .boton-editar:hover {
            background-color: #45a049;
        }
    </style>
    <?php
    include("conexion.php");

    $id = $_GET["id"];
    $result = $conexion->query("SELECT * FROM ficha_paciente WHERE id = $id");

    if ($result && $result->num_rows > 0) {
        $paciente = $result->fetch_assoc();
    } else {
        die("Paciente no encontrado");
    }
    ?>
</head>
<body>
    <h2>Ficha del Paciente</h2>
    
<?php foreach ($paciente as $campo => $valor): ?>
    <tr>
        <th><?php echo ucfirst(str_replace("_", " ", $campo)); ?></th>
        <td>
            <?php if ($campo === "firma_paciente" || $campo === "firma_profesional"): ?>
                <?php if (!empty($valor)): ?>
                    <img src="<?php echo $valor; ?>" alt="<?php echo $campo; ?>" style="max-width:300px; max-height:120px; border:1px solid #ccc;">
                <?php else: ?>
                    No registrada.
                <?php endif; ?>
            <?php else: ?>
                <?php echo htmlspecialchars($valor); ?>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>


</body>
</html>
