<?php
session_start();
include "funciones.php";

// Verificar si hay sesión activa o cookies
if (!isset($_SESSION["usuario"]) && (!isset($_COOKIE["usuario"]) || !isset($_COOKIE["contrasena"]))) {
    // Si no hay sesión ni cookies, redirigir a login
    header("Location: login.php");
    exit;
}

// Si existe cookie pero no sesión, recrear la sesión
if (!isset($_SESSION["usuario"]) && isset($_COOKIE["usuario"]) && isset($_COOKIE["contrasena"])) {
    $_SESSION["usuario"] = $_COOKIE["usuario"];
    $_SESSION["contrasena"] = $_COOKIE["contrasena"];
}

// Cargar productos desde base de datos
$productos = seleccionar($creds, "SELECT * FROM Productos");
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Interfaz de productos — Sistema OpticSight</title>
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
    min-height: 100vh; /* altura completa de la ventana */
    font-family: "Segoe UI", Arial, sans-serif;
    background-color: var(--color-fondo);
    color: var(--color-texto);
    margin: 0;
    line-height: 1.6;
}

    header {
        position: relative; /* necesario para que los hijos con absolute se posicionen respecto al header */
        background-color: var(--color-secundario);
        color: #FFF;
        padding: 20px 40px;
        text-align: center;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .logo {
        width: 300px;
        height: auto;
        border-radius: 8px;
    }

    main {
        flex: 1; /* ocupa todo el espacio restante */
        max-width: 1200px;
        margin: 20px auto;
        padding: 10px;
    }

    .acciones {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    button {
        background-color: var(--color-acento);
        color: #FFF;
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background-color: var(--color-secundario);
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    th, td {
        text-align: center;
        padding: 12px;
    }

    th {
        background-color: var(--color-primario);
        color: white;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) td {
        background-color: #F9F9F9;
    }

    tr:hover td {
        background-color: #E7F6F7;
    }

    form {
        background-color: #F5FCFD;
        border: 1px solid var(--color-primario);
        border-radius: 10px;
        padding: 20px;
        max-width: 600px;
        margin: 0 auto;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: 600;
        color: var(--color-secundario);
    }

    input, select {
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

    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.2);
        width: 90%;
        max-width: 450px;
        text-align: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-content h3 {
        color: var(--color-secundario);
        margin-top: 0;
    }

    .close {
        float: right;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        color: var(--color-acento);
    }

    .close:hover {
        color: var(--color-secundario);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

footer {
    background-color: var(--color-secundario);
    color: #FFF;
    text-align: center;
    padding: 15px;
    font-size: 14px;
    flex-shrink: 0; /* evita que el footer se encoja */
}
    
.header-content {
    display: flex;
    align-items: right;
    gap: 15px;
}

.btn-logout {
    position: absolute;  /* permite colocarlo manualmente */
    top: 20px;           /* distancia desde el top del header */
    right: 40px;         /* distancia desde la derecha del header */
    background-color: var(--color-acento);
    color: #FFF;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-logout:hover {
    background-color: var(--color-secundario);
}

</style>
</head>
<body>

<header role="banner">
    <div class="header-content">
        <img src="OpticSight.png" alt="LogoHeader" class="logo">
        <h1>Gestión de Productos</h1>
        <!-- Botón de cerrar sesión -->
        <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
</header>


<main role="main">
    <section aria-labelledby="listaProductos">
        <h2 id="listaProductos" style="text-align:center; color:var(--color-secundario)">Lista de Productos Existentes</h2>

        <div class="acciones">
            <button onclick="abrirModal('modalAgregar')">Añadir producto nuevo</button>
            <button onclick="abrirModal('modalActualizar')">Actualizar producto</button>
            <button onclick="abrirModal('modalEliminar')">Eliminar producto</button>
        </div>

        <table role="table" aria-label="Lista de productos registrados">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productos as $p): ?>
                <tr>
                    <td><?= $p[0] ?></td>
                    <td><?= htmlspecialchars($p[1]) ?></td>
                    <td><?= $p[2] ?></td>
                    <td>$<?= number_format($p[3], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<!-- Modal: Agregar producto -->
<div id="modalAgregar" class="modal" role="dialog" aria-modal="true" aria-labelledby="tituloAgregar">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalAgregar')" aria-label="Cerrar ventana">&times;</span>
        <h3 id="tituloAgregar">Agregar nuevo producto</h3>
        <form action="nuevop.php" method="post" aria-label="Formulario de registro de producto">
            <label>Nombre del producto:</label>
            <input type="text" name="nombre" required>

            <label>Cantidad:</label>
            <input type="number" name="cantidad" required>

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" required>

            <input type="submit" value="Agregar Producto">
        </form>
    </div>
</div>

<!-- Modal: Actualizar producto -->
<div id="modalActualizar" class="modal" role="dialog" aria-modal="true" aria-labelledby="tituloActualizar">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalActualizar')" aria-label="Cerrar ventana">&times;</span>
        <h3 id="tituloActualizar">Actualizar producto</h3>
        <form action="editarp.php" method="get">
            <label>Selecciona el producto a actualizar:</label>
            <select name="id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach($productos as $p): ?>
                    <option value="<?= $p[0] ?>"><?= htmlspecialchars($p[1]) ?> (ID: <?= $p[0] ?>)</option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Editar">
        </form>
    </div>
</div>

<!-- Modal: Eliminar producto -->
<div id="modalEliminar" class="modal" role="dialog" aria-modal="true" aria-labelledby="tituloEliminar">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalEliminar')" aria-label="Cerrar ventana">&times;</span>
        <h3 id="tituloEliminar">Eliminar producto</h3>
        <form action="eliminarp.php" method="post" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
            <label>Selecciona el producto a eliminar:</label>
            <select name="id" required>
                <option value="">-- Selecciona --</option>
                <?php foreach($productos as $p): ?>
                    <option value="<?= $p[0] ?>"><?= htmlspecialchars($p[1]) ?> (ID: <?= $p[0] ?>)</option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Eliminar">
        </form>
    </div>
</div>

<footer role="contentinfo">
    &copy; <?= date("Y") ?> Interfaz de productos — Sistema OpticSight 2024-2025
</footer>

<script>
function abrirModal(id) {
    document.getElementById(id).style.display = "flex";
}
function cerrarModal(id) {
    document.getElementById(id).style.display = "none";
}
window.onclick = function(e) {
    document.querySelectorAll(".modal").forEach(m => {
        if (e.target === m) m.style.display = "none";
    });
}
</script>

</body>
</html>
