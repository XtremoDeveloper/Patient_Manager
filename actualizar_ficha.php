<?php
include("conexion.php");

$id = $_GET["id"];

// Obtener datos del paciente
$result = $conexion->query("SELECT * FROM ficha_paciente WHERE id = $id");
if ($result && $result->num_rows > 0) {
    $paciente = $result->fetch_assoc();
} else {
    die("Paciente no encontrado");
}

// Si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diagnostico = $_POST["diagnostico"];
    $tratamiento = $_POST["tratamiento"];
    $plan_atencion = $_POST["plan_atencion"];
    $seguimiento = $_POST["seguimiento"];
    $observaciones = $_POST["observaciones"];
    $examenes = $_POST["examenes"];
    $signos_vitales = $_POST["signos_vitales"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $estado_civil = $_POST["estado_civil"];
    $ocupacion = $_POST["ocupacion"];
    $contacto_nombre = $_POST["contacto_emergencia_nombre"];
    $contacto_parentesco = $_POST["contacto_emergencia_parentesco"];
    $contacto_telefono = $_POST["contacto_emergencia_telefono"];
    $antecedentes = $_POST["antecedentes"];
    $habitos = $_POST["habitos"];

    $sql = "UPDATE ficha_paciente SET 
        diagnostico='$diagnostico',
        tratamiento='$tratamiento',
        plan_atencion='$plan_atencion',
        seguimiento='$seguimiento',
        observaciones='$observaciones',
        examenes='$examenes',
        signos_vitales='$signos_vitales',
        direccion='$direccion',
        telefono='$telefono',
        correo='$correo',
        estado_civil='$estado_civil',
        ocupacion='$ocupacion',
        contacto_emergencia_nombre='$contacto_nombre',
        contacto_emergencia_parentesco='$contacto_parentesco',
        contacto_emergencia_telefono='$contacto_telefono',
        antecedentes='$antecedentes',
        habitos='$habitos'
        WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        header("Location: ficha_paciente.php?id=$id");
        exit();
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <style>
        body { font-family: Arial; background: #fff; padding: 20px; }
        form { max-width: 600px; margin: auto; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        textarea, input, select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Editar Información del Paciente</h2>

<form method="POST">
    <label>Diagnóstico</label>
    <textarea name="diagnostico"><?php echo $paciente['diagnostico']; ?></textarea>

    <label>Tratamiento</label>
    <textarea name="tratamiento"><?php echo $paciente['tratamiento']; ?></textarea>

    <label>Plan de atención</label>
    <textarea name="plan_atencion"><?php echo $paciente['plan_atencion']; ?></textarea>

    <label>Seguimiento</label>
    <textarea name="seguimiento"><?php echo $paciente['seguimiento']; ?></textarea>

    <label>Observaciones</label>
    <textarea name="observaciones"><?php echo $paciente['observaciones']; ?></textarea>

    <label>Exámenes</label>
    <textarea name="examenes"><?php echo $paciente['examenes']; ?></textarea>

    <label>Signos vitales</label>
    <textarea name="signos_vitales"><?php echo $paciente['signos_vitales']; ?></textarea>

    <label>Dirección</label>
    <input type="text" name="direccion" value="<?php echo $paciente['direccion']; ?>">

    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?php echo $paciente['telefono']; ?>">

    <label>Correo electrónico</label>
    <input type="email" name="correo" value="<?php echo $paciente['correo']; ?>">

    <label>Estado civil</label>
    <input type="text" name="estado_civil" value="<?php echo $paciente['estado_civil']; ?>">

    <label>Ocupación</label>
    <input type="text" name="ocupacion" value="<?php echo $paciente['ocupacion']; ?>">

    <label>Contacto de emergencia - Nombre</label>
    <input type="text" name="contacto_emergencia_nombre" value="<?php echo $paciente['contacto_emergencia_nombre']; ?>">

    <label>Contacto de emergencia - Parentesco</label>
    <input type="text" name="contacto_emergencia_parentesco" value="<?php echo $paciente['contacto_emergencia_parentesco']; ?>">

    <label>Contacto de emergencia - Teléfono</label>
    <input type="text" name="contacto_emergencia_telefono" value="<?php echo $paciente['contacto_emergencia_telefono']; ?>">

    <label>Antecedentes personales y familiares</label>
    <textarea name="antecedentes"><?php echo $paciente['antecedentes']; ?></textarea>

    <label>Hábitos</label>
    <textarea name="habitos"><?php echo $paciente['habitos']; ?></textarea>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
