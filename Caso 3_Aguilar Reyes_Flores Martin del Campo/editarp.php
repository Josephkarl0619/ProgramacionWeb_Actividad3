<?php
include "funciones.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $datos = seleccionar($creds, "SELECT * FROM Productos WHERE Cod_Producto = ?", [$id]);
    if (count($datos) > 0) {
        $producto = $datos[0];
    } else {
        die("Producto no encontrado");
    }
} else {
    die("No se indicó producto a editar");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Producto — Sistema OpticSight</title>
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
    flex: 1;
    width: 100%;
    max-width: 500px;
    margin: 30px auto;
    padding: 20px;
    background-color: #F5FCFD;
    border: 1px solid var(--color-primario);
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}

label {
    display: block;
    margin-top: 10px;
    font-weight: 600;
    color: var(--color-secundario);
}

input[type="text"],
input[type="number"] {
    width: 95%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-top: 5px;
    font-size: 14px;
}

input[type="submit"] {
    background-color: var(--color-primario);
    color: #FFF;
    border: none;
    border-radius: 6px;
    padding: 10px 20px;
    font-size: 15px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: var(--color-secundario);
}

a.btn-cancel {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 20px;
    background-color: var(--color-acento);
    color: #FFF;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: background-color 0.3s;
}

a.btn-cancel:hover {
    background-color: var(--color-secundario);
}
</style>
</head>
<body>

<header>
    <h1>Sistema OpticSight — Editar Producto</h1>
</header>

<main>
    <form action="actualizarp.php" method="post">
        <input type="hidden" name="id" value="<?= $producto[0] ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto[1]) ?>" required>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" value="<?= $producto[2] ?>" required>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto[3] ?>" required>

        <input type="submit" value="Actualizar">
    </form>

    <a href="homei.php" class="btn-cancel">Cancelar</a>
</main>

</body>
</html>
