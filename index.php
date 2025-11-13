<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Pacientes</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      display: flex;
      background-color: #0097b2;
    }
    .sidebar {
      width: 220px;
      background-color: #343a40;
      height: 100vh;
      color: white;
      display: flex;
      flex-direction: column;
    }
    .sidebar a {
      padding: 15px;
      text-decoration: none;
      color: white;
      display: block;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .main-content {
      flex: 1;
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2 style="text-align:center; padding: 20px;">🩺 IA Health</h2>
    <a href="index.php">Inicio</a>
    <a href="formulario_registro.php">Registrar Paciente</a>
    <a href="?seccion=listas">Todas las Listas</a>
  </div>

  <div class="main-content">
    <?php
      if (isset($_GET['seccion'])) {
        if ($_GET['seccion'] == 'formulario') {
          include 'formulario_registro.php';
        } elseif ($_GET['seccion'] == 'listas') {
          include 'lista_pacientes.php';
        } else {
          echo "<h3>Sección no válida.</h3>";
        }
      } else {
    echo '<h2 style=" text-align:center; padding: 40px; color: white;">Bienvenido al sistema de pacientes IA Health 🩺</h2>';
    echo '<p style=" text-align:center; padding: 20px; color: white;">Usa el menú de la izquierda para comenzar.</p>';
}
    ?>
  </div>
</body>
</html>
