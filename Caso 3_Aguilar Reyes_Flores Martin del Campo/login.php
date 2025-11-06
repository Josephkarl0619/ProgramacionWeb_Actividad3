<?php
session_start();
include "funciones.php";

$mensaje = "";

// Verificar si hay cookie activa
if(isset($_COOKIE["usuario"]) && isset($_COOKIE["contrasena"])) {
    $_SESSION["usuario"] = $_COOKIE["usuario"];
    $_SESSION["contrasena"] = $_COOKIE["contrasena"];
    header("Location: homei.php"); // <-- redirigir a homei
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["usuario"] ?? "";
    $pass = $_POST["contrasena"] ?? "";
    $recordar = isset($_POST["recordar"]);

    try {
        // Intentar conexión con PDO
        $dsn = "odbc:Driver={ODBC Driver 17 for SQL Server};Server={$creds[0]};Database={$creds[1]};Encrypt=no;TrustServerCertificate=yes;";
        $conn = new PDO($dsn, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Guardar sesión
        $_SESSION["usuario"] = $user;
        $_SESSION["contrasena"] = $pass;

        // Guardar cookies si se seleccionó "Recordarme"
        if($recordar){
            setcookie("usuario", $user, time()+604800, "/");
            setcookie("contrasena", $pass, time()+604800, "/");
        }

        $conn = null;
        header("Location: homei.php"); // <-- redirigir a homei
        exit;

    } catch (PDOException $e) {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Sistema OpticSight</title>
<style>
:root {
    --color-primario: rgb(112,205,212);
    --color-secundario: rgb(58,85,164);
    --color-acento: rgb(246,140,38);
    --color-fondo: #FFFFFF;
    --color-texto: #333;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: var(--color-fondo);
    color: var(--color-texto);
    margin: 0;
    line-height: 1.6;
    justify-content: center;
    align-items: center;
}

header {
    background-color: var(--color-secundario);
    color: #FFF;
    padding: 20px 40px;
    text-align: center;
    width: 100%;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.logo {
    width: 150px;
    height: auto;
    border-radius: 8px;
}

h1 {
    margin: 0;
    font-size: 1.8em;
    letter-spacing: 0.5px;
}

form {
    background-color: #F5FCFD;
    border: 1px solid var(--color-primario);
    border-radius: 10px;
    padding: 30px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: 600;
    color: var(--color-secundario);
}

input[type="text"],
input[type="password"] {
    width: 95%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

input[type="submit"] {
    background-color: var(--color-primario);
    color: #FFF;
    border: none;
    border-radius: 6px;
    padding: 10px;
    font-size: 15px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: var(--color-secundario);
}

.mensaje {
    color: red;
    text-align: center;
    font-weight: bold;
}

footer {
    background-color: var(--color-secundario);
    color: #FFF;
    text-align: center;
    padding: 15px;
    font-size: 14px;
    flex-shrink: 0;
    width: 100%;
    margin-top: auto;
}
</style>
</head>
<body>

<header>
    <div class="header-content">
        <img src="OpticSight.png" alt="Logo" class="logo">
        <h1>Iniciar Sesión</h1>
    </div>
</header>

<form method="post" aria-label="Formulario de login">
    <label for="usuario">Usuario:</label>
    <input type="text" id="usuario" name="usuario" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required>

    <div class="checkbox-container">
        <input type="checkbox" id="recordar" name="recordar">
        <label for="recordar">Recordar sesión</label>
    </div>

    <input type="submit" value="Ingresar">
    <?php if($mensaje): ?>
        <div class="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>
</form>

<footer>
    &copy; <?= date("Y") ?> Sistema OpticSight — Login
</footer>

</body>
</html>
