<?php
$conexion = new mysqli("localhost", "root", "", "pacientes");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

?>
