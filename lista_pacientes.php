<?php
include("conexion.php");
$pacientes = $conexion->query("SELECT id, nombres_apellidos FROM ficha_paciente ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Códigos QR Generados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            margin: 0;
            background-color: #0097b2;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 220px;
            background-color: #263238;
            padding-top: 20px;
            color: white;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #37474f;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        .table {
            background-color: #eceff1;
            border-radius: 8px;
            overflow: hidden;
        }
        .btn-qr {
            background-color: #ff7043;
            color: white;
            border: none;
        }
        .btn-editar {
            background-color: #4caf50;
            color: white;
            border: none;
        }
        #buscador {
            max-width: 300px;
            margin: 10px auto;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>🩺 IA Health</h4>
    <a href="index.php">Inicio</a>
    <a href="formulario_registro.php">Registrar Paciente</a>
    <a href="lista_pacientes.php">Todas las Listas</a>
</div>

<!-- Contenido -->
<div class="content">
    <h3 class="text-center fw-bold">Lista de Códigos QR Generados</h3>

    <!-- Buscador -->
    <input type="text" id="buscador" class="form-control" placeholder="Buscar por ID o Nombre...">

    <table class="table table-striped text-center align-middle mt-4" id="tablaPacientes">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($p = $pacientes->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo $p['nombres_apellidos']; ?></td>
                <td>
                    <a href="qr/paciente_<?php echo $p['id']; ?>.png" target="_blank" class="btn btn-sm btn-qr">QR</a>
                    <a href="ficha_paciente.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Ver
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Script para el buscador -->
<script>
document.getElementById("buscador").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaPacientes tbody tr");

    filas.forEach(function(fila) {
        let id = fila.cells[0].textContent.toLowerCase();
        let nombre = fila.cells[1].textContent.toLowerCase();

        if (id.includes(filtro) || nombre.includes(filtro)) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
});
</script>

</body>
</html>
