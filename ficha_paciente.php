<?php
include("conexion.php");

$id = $_GET["id"];
$modo = isset($_GET["modo"]) && $_GET["modo"] === "editar" ? "editar" : "ver";

// Obtener datos del paciente
$result = $conexion->query("SELECT * FROM ficha_paciente WHERE id = $id");
if ($result && $result->num_rows > 0) {
    $paciente = $result->fetch_assoc();
} else {
    die("Paciente no encontrado");
}

// Guardar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "guardar") {
    $campos_editables = [
        "diagnostico", "tratamiento", "plan_atencion", "seguimiento", "observaciones",
        "examenes", "signos_vitales", "direccion", "telefono", "correo", "estado_civil",
        "ocupacion", "contacto_emergencia_nombre", "contacto_emergencia_parentesco",
        "contacto_emergencia_telefono", "antecedentes", "habitos"
    ];

    $updates = [];
    foreach ($campos_editables as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $conexion->real_escape_string($_POST[$campo]);
            $updates[] = "$campo='$valor'";
        }
    }

    if (!empty($updates)) {
        $sql = "UPDATE ficha_paciente SET " . implode(", ", $updates) . " WHERE id=$id";
        if ($conexion->query($sql) === TRUE) {
            header("Location: ficha_paciente.php?id=$id");
            exit();
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
    }
}

// Eliminar paciente
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "eliminar") {
    $conexion->query("DELETE FROM ficha_paciente WHERE id=$id");
    header("Location: lista_pacientes.php"); // Cambia a tu página principal
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $modo === "editar" ? "(Editando) " : ""; ?>Ficha del Paciente</title>
    <style>
        body { font-family: Arial; background: #fff; padding: 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h2 {
            text-align: center;
            flex: 1;
        }
        .boton {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .boton:hover { background-color: #45a049; }
        .boton-rojo {
            background-color: #d9534f;
        }
        .boton-rojo:hover {
            background-color: #c9302c;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ccc; }
        th { background-color: #f2f2f2; width: 30%; }
        input, textarea {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
        .acciones {
            margin-top: 20px;
            text-align: center;
        }
    </style>
    <script>
        function confirmarEliminacion() {
            if (confirm("¿Estás seguro de eliminar este documento?")) {
                document.getElementById("accion").value = "eliminar";
                document.getElementById("formPaciente").submit();
            }
        }
    </script>
</head>
<body>

<div class="header">
    <h2><?php echo $modo === "editar" ? "(Editando) " : ""; ?>Ficha del Paciente</h2>
    <?php if ($modo === "ver"): ?>
        <a href="?id=<?php echo $id; ?>&modo=editar" class="boton">Editar</a>
    <?php else: ?>
        <span></span>
    <?php endif; ?>
</div>

<form method="POST" id="formPaciente">
    <input type="hidden" name="accion" id="accion" value="">
    <table>
        <?php
        $campos_editables = [
            "diagnostico", "tratamiento", "plan_atencion", "seguimiento", "observaciones",
            "examenes", "signos_vitales", "direccion", "telefono", "correo", "estado_civil",
            "ocupacion", "contacto_emergencia_nombre", "contacto_emergencia_parentesco",
            "contacto_emergencia_telefono", "antecedentes", "habitos"
        ];

        foreach ($paciente as $campo => $valor):
    echo "<tr>";
    echo "<th>" . ucfirst(str_replace("_", " ", $campo)) . "</th>";
    echo "<td>";
    
    // Si es modo editar
    if ($modo === "editar" && in_array($campo, $campos_editables)) {
        if (strlen($valor) > 50 || in_array($campo, ["diagnostico","tratamiento","plan_atencion","seguimiento","observaciones","examenes","antecedentes","habitos","signos_vitales"])) {
            echo "<textarea name='$campo'>$valor</textarea>";
        } else {
            echo "<input type='text' name='$campo' value='$valor'>";
        }
    } else {
        // Mostrar imagen si es firma
        if ($campo === "firma_paciente" || $campo === "firma_profesional") {
            if (!empty($valor)) {
                echo "<img src='" . $valor . "' alt='$campo' style='max-width:300px; max-height:120px; border:1px solid #ccc;'>";
            } else {
                echo "No registrada.";
            }
        } else {
            echo htmlspecialchars($valor);
        }
    }
    
    echo "</td>";
    echo "</tr>";
endforeach;
        ?>
    </table>

    <div class="acciones">
    <?php if ($modo === "ver"): ?>
        <a href="lista_pacientes.php" class="boton">Volver</a>
    <?php else: ?>
        <a href="ficha_paciente.php?id=<?php echo $id; ?>" class="boton" style="background-color: gray;">Cancelar</a>
        <button type="submit" class="boton" onclick="document.getElementById('accion').value='guardar'">Guardar</button>
        <button type="button" class="boton boton-rojo" onclick="confirmarEliminacion()">Eliminar</button>
    <?php endif; ?>
</div>

</form>

</body>
</html>
