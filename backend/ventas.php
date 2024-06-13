<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerVentas') {
    obtenerVentas();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearVenta') {
    crearVenta();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarVenta') {
    eliminarVenta();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarVenta') {
    actualizarVenta();
}

function obtenerVentas() {
    global $conn;

    $sql = "SELECT * FROM ventas";
    $result = $conn->query($sql);

    $ventas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ventas[] = $row;
        }
    }
    echo json_encode($ventas);
}

function crearVenta() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_usuario']) || !isset($data['fecha']) || !isset($data['total']) || !isset($data['metodo_pago'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO ventas (id_usuario, fecha, total, metodo_pago) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $data['id_usuario'], $data['fecha'], $data['total'], $data['metodo_pago']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Venta creada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear la venta: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarVenta() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_venta'])) {
        echo json_encode(['error' => 'ID de venta no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM ventas WHERE id_venta = ?");
    $stmt->bind_param("i", $data['id_venta']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Venta eliminada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar la venta: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarVenta() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_venta']) || !isset($data['id_usuario']) || !isset($data['fecha']) || !isset($data['total']) || !isset($data['metodo_pago'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE ventas SET id_usuario=?, fecha=?, total=?, metodo_pago=? WHERE id_venta=?");
    $stmt->bind_param("isdsi", $data['id_usuario'], $data['fecha'], $data['total'], $data['metodo_pago'], $data['id_venta']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Venta actualizada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar la venta: ' . $conn->error]);
    }
    $stmt->close();
}
?>
