<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            background-color: #0097b2;
        }
        /* Sidebar fija */
        .sidebar {
            width: 220px;
            background-color: #263238;
            color: white;
            padding-top: 20px;
            flex-shrink: 0;
        }
        .sidebar h4 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display:block; padding:12px 20px; color:white; text-decoration:none; }
        .sidebar a:hover { background-color:#37474f; }

        /* Form container */
        .form-container { flex:1; padding:40px 20px; display:flex; justify-content:center; align-items:center; }
        .form-box {
            background:white; padding:20px; border-radius:8px; max-width:900px; width:100%;
            height:90vh; overflow-y:auto; box-shadow:0 4px 10px rgba(0,0,0,0.2);
        }
        .form-box::-webkit-scrollbar { width:8px; }
        .form-box::-webkit-scrollbar-thumb { background-color:#0097b2; border-radius:4px; }

        /* header with ID */
        .form-header { display:flex; justify-content:space-between; align-items:center;
            border-bottom:2px solid #0097b2; padding-bottom:5px; margin-bottom:20px; position:relative; }
        .form-header h2 { margin:0; font-size:1.5rem; font-weight:bold; }
        .form-id {
            font-size:1.1rem; font-weight:bold; color:#0097b2; background:#f3f3f3;
            border:1px solid #ccc; padding:6px 10px; border-radius:6px; cursor:pointer;
            position:relative;
        }
        .form-id:hover { background:#eaf9ff; }
        .form-id::after {
            content: "Copiar ID";
            position:absolute; bottom:-26px; left:50%; transform:translateX(-50%);
            background:#333; color:#fff; font-size:12px; padding:3px 6px; border-radius:4px;
            opacity:0; pointer-events:none; transition:opacity .18s;
        }
        .form-id:hover::after { opacity:1; }

        /* toast */
        #toast {
            visibility:hidden; min-width:160px; background:#333; color:#fff; text-align:center;
            border-radius:6px; padding:8px 12px; position:fixed; top:18px; right:18px; z-index:2000;
            opacity:0; transition:opacity .3s, visibility .3s;
        }
        #toast.show { visibility:visible; opacity:1; }
        /* success box */
        .success-box { background:#e9f7ee; border:1px solid #bde5c8; padding:10px; border-radius:6px; margin-bottom:12px; }
    </style>

</head>
<body>

<!-- Barra lateral -->
<div class="sidebar">
    <h4 style="text-align:center; padding: 20px;">🩺 IA Health</h4>
    <a href="index.php">Inicio</a>
    <a href="formulario_registro.php">Registrar Paciente</a>
    <a href="lista_pacientes.php">Todas las Listas</a>
</div>

<!-- Contenedor del formulario -->
<div class="form-container">
    <div class="form-box">
        <div class="form-header">
            <h2>Registro de Paciente</h2>
<?php
include("conexion.php");
include("phpqrcode/qrlib.php");
$result = $conexion->query("SELECT MAX(id) AS ultimo_id FROM ficha_paciente");
$row = $result ? $result->fetch_assoc() : null;
$siguienteID = ($row['ultimo_id'] ?? 0) + 1;
$registro_ok = false;
$ultimoGeneradoId = null;
$qr_path = '';
$errorMsg = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $datos = [];
    foreach ($_POST as $key => $value) {
        $datos[$key] = $conexion->real_escape_string($value);
    }

    // Manejar subida de archivos de firma
    $carpetaFirmas = "firmas/";
    if (!file_exists($carpetaFirmas)) {
        mkdir($carpetaFirmas, 0777, true);
    }

    // Firma del paciente
    if (!empty($_FILES['firma_paciente']['name'])) {
        $nombreArchivo = "paciente_" . time() . "_" . basename($_FILES['firma_paciente']['name']);
        $ruta = $carpetaFirmas . $nombreArchivo;
        move_uploaded_file($_FILES['firma_paciente']['tmp_name'], $ruta);
        $datos['firma_paciente'] = $ruta;
    }

    // Firma del profesional
    if (!empty($_FILES['firma_profesional']['name'])) {
        $nombreArchivo = "profesional_" . time() . "_" . basename($_FILES['firma_profesional']['name']);
        $ruta = $carpetaFirmas . $nombreArchivo;
        move_uploaded_file($_FILES['firma_profesional']['tmp_name'], $ruta);
        $datos['firma_profesional'] = $ruta;
    }

    // Insertar en la BD
if (count($datos) > 0) {
    $campos = implode(",", array_keys($datos));
    $valores = "'" . implode("','", $datos) . "'";
    $query = "INSERT INTO ficha_paciente ($campos) VALUES ($valores)";

    if ($conexion->query($query)) {
        $ultimoGeneradoId = $conexion->insert_id;
        $registro_ok = true;

        // Generar QR
        $carpetaQR = "qr/";
        if (!file_exists($carpetaQR)) {
            mkdir($carpetaQR, 0777, true);
        }
        $qr_path = $carpetaQR . "paciente_" . $ultimoGeneradoId . ".png";
        $contenidoQR = "http://localhost/proyecto_qr_pacientes/ficha_paciente.php?id=" . $ultimoGeneradoId;
        QRcode::png($contenidoQR, $qr_path, QR_ECLEVEL_L, 4);
    } else {
        $errorMsg = "Error al registrar: " . $conexion->error;
    }
}
}

?>
            <!-- ID que se muestra y copia -->
            <span class="form-id" id="siguienteID" title="Copiar ID" onclick="copiarID('<?php echo $siguienteID; ?>')">
                <?php echo $siguienteID; ?>
            </span>
        </div>

        <!-- Mensaje de éxito / error -->
        <?php if ($registro_ok): ?>
            <div class="success-box">
                Registro exitoso. ID generado: <strong><?php echo $ultimoGeneradoId; ?></strong>
                <?php if ($qr_path): ?>
                    &nbsp; <a href="<?php echo $qr_path; ?>" target="_blank">Ver QR</a>
                <?php endif; ?>
            </div>
        <?php elseif (!empty($errorMsg)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nombres y Apellidos</label>
                    <input type="text" name="nombres_apellidos" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>DNI</label>
                    <input type="text" name="dni" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label>Edad</label>
                    <input type="number" name="edad" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label>Sexo</label>
                    <select name="sexo" class="form-control">
                        <option>Masculino</option>
                        <option>Femenino</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Correo</label>
                    <input type="email" name="correo" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Estado Civil</label>
                    <input type="text" name="estado_civil" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Ocupación</label>
                    <input type="text" name="ocupacion" class="form-control">
                </div>
            </div>

            <h5>Contacto de Emergencia</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" name="contacto_emergencia_nombre" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Parentesco</label>
                    <input type="text" name="contacto_emergencia_parentesco" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="contacto_emergencia_telefono" class="form-control">
                </div>
            </div>

            <h5>Datos Médicos</h5>
            <div class="mb-3">
                <label>Motivo Consulta</label>
                <textarea name="motivo_consulta" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Diagnóstico</label>
                <textarea name="diagnostico" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Tratamiento</label>
                <textarea name="tratamiento" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Antecedentes</label>
                <textarea name="antecedentes" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Hábitos</label>
                <textarea name="habitos" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Signos Vitales</label>
                <textarea name="signos_vitales" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Exámenes</label>
                <textarea name="examenes" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Plan Atención</label>
                <textarea name="plan_atencion" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Seguimiento</label>
                <textarea name="seguimiento" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control"></textarea>
            </div>

            <h5>Firmas</h5>
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Firma del Paciente</label>
        <input type="file" name="firma_paciente" class="form-control" 
               accept="image/*" capture="camera" onchange="previewImage(event,'previewPaciente')">
        <div class="mt-2">
            <img id="previewPaciente" src="" alt="Vista previa firma paciente" 
                 style="max-width:220px; max-height:80px; border:1px solid #ccc; border-radius:4px; display:none;">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label>Firma del Profesional</label>
        <input type="file" name="firma_profesional" class="form-control" 
               accept="image/*" capture="camera" onchange="previewImage(event,'previewProfesional')">
        <div class="mt-2">
            <img id="previewProfesional" src="" alt="Vista previa firma profesional" 
                 style="max-width:220px; max-height:80px; border:1px solid #ccc; border-radius:4px; display:none;">
        </div>
    </div>
</div>

 <div class="col-md-4 mb-3">
                    <label>Fecha de Firma</label>
                    <input type="date" name="fecha_firma" class="form-control">
                </div>
            <button class="btn btn-success w-100 mt-3" type="submit">Registrar y Generar QR</button>
        </form>
    </div>
</div>

<!-- Toast -->
<div id="toast">ID copiado</div>

<script>
function showToast(text) {
    const t = document.getElementById('toast');
    t.textContent = text;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 1500);
}

function copiarID(id) {
    if (!navigator.clipboard) {
        // Fallback
        const ta = document.createElement('textarea');
        ta.value = id;
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); showToast('ID copiado: ' + id); } catch(e){ alert('Copia manual: ' + id); }
        document.body.removeChild(ta);
        return;
    }
    navigator.clipboard.writeText(String(id)).then(() => {
        showToast('ID copiado: ' + id);
    }).catch(err => {
        alert('Error al copiar: ' + err);
    });
}

function previewImage(event, idPreview) {
    const input = event.target;
    const file = input.files[0];
    const preview = document.getElementById(idPreview);

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.style.display = "none";
    }
}

</script>

</body>
</html>
