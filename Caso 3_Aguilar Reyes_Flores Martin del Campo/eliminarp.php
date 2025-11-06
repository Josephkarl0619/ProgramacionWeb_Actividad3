<?php
include "funciones.php";

$mensaje = "";

if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $query = "DELETE FROM Productos WHERE Cod_Producto = ?";
    $res = eliminar($creds, $query, [$id]);

    if ($res) {
        $mensaje = "Producto eliminado correctamente";
    } else {
        $mensaje = "Error al eliminar producto";
    }
} else {
    $mensaje = "No se indicó ID para eliminar";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Actualizar Producto — Sistema OpticSight</title>
<style>
:root {
    --color-primario: rgb(112,205,212);
    --color-secundario: rgb(58,85,164);
    --color-acento: rgb(246,140,38);
    --color-fondo: #FFFFFF;
    --color-texto: #333;
}

body {
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: var(--color-fondo);
    color: var(--color-texto);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: top;
    min-height: 100vh;
    margin: 0;
    line-height: 1.6;
}

header {
    background-color: var(--color-secundario);
    color: #FFF;
    width: 100%;
    padding: 20px;
    text-align: center;
}

h1 {
    font-size: 1.5em;
    margin: 0;
}

main {
    width: 80%;
    max-width: 400px;
    margin: 50px auto;
    height: 150px;
    overflow: hidden;
    padding: 15px;
    background-color: #F5FCFD;
    border: 1px solid var(--color-primario);
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    text-align: center;
    flex: 0 0 auto;
}

a.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: var(--color-acento);
    color: #FFF;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
}

a.btn:hover {
    background-color: var(--color-secundario);
}
</style>
</head>
<body>

<header>
    <h1>Sistema OpticSight — Actualizar Producto</h1>
</header>

<main>
    <h1><?= htmlspecialchars($mensaje) ?></h1>
    <a href="homei.php" class="btn">Regresar</a>
</main>

</body>
</html>
