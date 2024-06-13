<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerInventarios') {
    obtenerInventarios();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearInventario') {
    crearInventario();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarInventario') {
    eliminarInventario();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarInventario') {
    actualizarInventario();
}

function obtenerInventarios() {
    global $conn;

    $sql = "SELECT * FROM inventario";
    $result = $conn->query($sql);

    $inventarios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $inventarios[] = $row;
        }
    }
    echo json_encode($inventarios);
}

function crearInventario() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_producto']) || !isset($data['cantidad']) || !isset($data['fecha_actualizacion'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO inventario (id_producto, cantidad, fecha_actualizacion) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $data['id_producto'], $data['cantidad'], $data['fecha_actualizacion']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Inventario creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el inventario: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarInventario() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_inventario'])) {
        echo json_encode(['error' => 'ID de inventario no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM inventario WHERE id_inventario = ?");
    $stmt->bind_param("i", $data['id_inventario']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Inventario eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el inventario: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarInventario() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_inventario']) || !isset($data['id_producto']) || !isset($data['cantidad']) || !isset($data['fecha_actualizacion'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE inventario SET id_producto=?, cantidad=?, fecha_actualizacion=? WHERE id_inventario=?");
    $stmt->bind_param("iisi", $data['id_producto'], $data['cantidad'], $data['fecha_actualizacion'], $data['id_inventario']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Inventario actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el inventario: ' . $conn->error]);
    }
    $stmt->close();
}
?>
