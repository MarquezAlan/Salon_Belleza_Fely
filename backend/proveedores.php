<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerProveedores') {
    obtenerProveedores();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearProveedor') {
    crearProveedor();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarProveedor') {
    eliminarProveedor();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarProveedor') {
    actualizarProveedor();
}

function obtenerProveedores() {
    global $conn;

    $sql = "SELECT * FROM proveedores";
    $result = $conn->query($sql);

    $proveedores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
    }
    echo json_encode($proveedores);
}

function crearProveedor() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre']) || !isset($data['contacto']) || !isset($data['telefono']) || !isset($data['email'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['nombre'], $data['contacto'], $data['telefono'], $data['email']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Proveedor creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el proveedor: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarProveedor() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_proveedor'])) {
        echo json_encode(['error' => 'ID de proveedor no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
    $stmt->bind_param("i", $data['id_proveedor']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Proveedor eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el proveedor: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarProveedor() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_proveedor']) || !isset($data['nombre']) || !isset($data['contacto']) || !isset($data['telefono']) || !isset($data['email'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE proveedores SET nombre=?, contacto=?, telefono=?, email=? WHERE id_proveedor=?");
    $stmt->bind_param("ssssi", $data['nombre'], $data['contacto'], $data['telefono'], $data['email'], $data['id_proveedor']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Proveedor actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el proveedor: ' . $conn->error]);
    }
    $stmt->close();
}
?>
