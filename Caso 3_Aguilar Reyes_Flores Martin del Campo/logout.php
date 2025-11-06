<?php
session_start();

// Eliminar cookies
setcookie("usuario", "", time() - 3600, "/");
setcookie("contrasena", "", time() - 3600, "/");

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login
header("Location: login.php");
exit;
?>
