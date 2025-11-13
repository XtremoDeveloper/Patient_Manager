<?php
include 'phpqrcode/phpqrcode.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $url = "https://tusitio.com/ver_paciente.php?id=" . $id; // Cambia por tu dominio real
  QRcode::png($url);
}
?>