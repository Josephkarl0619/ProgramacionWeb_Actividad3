<?php
ini_set('display_errors', E_ALL);

// Credenciales del servidor SQL Server
$creds = ["CPC", "opticsight", "sa", "happy1521"];

function conectar($creds) {
    $dsn = "odbc:Driver={ODBC Driver 17 for SQL Server};Server={$creds[0]};Database={$creds[1]};Encrypt=no;TrustServerCertificate=yes;";
    try {
        $conn = new PDO($dsn, $creds[2], $creds[3]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function insertar($creds, $query, $params = []) {
    $conn = conectar($creds);
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $conn = null;
    return true; // ya no usamos lastInsertId()
}


function seleccionar($creds, $query, $params = []) {
    $conn = conectar($creds);
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $regs = $stmt->fetchAll(PDO::FETCH_NUM);
    $conn = null;
    return $regs;
}

function actualizar($creds, $query, $params = []) {
    $conn = conectar($creds);
    $stmt = $conn->prepare($query);
    $res = $stmt->execute($params);
    $conn = null;
    return $res;
}

function eliminar($creds, $query, $params = []) {
    $conn = conectar($creds);
    $stmt = $conn->prepare($query);
    $res = $stmt->execute($params);
    $conn = null;
    return $res;
}
?>
