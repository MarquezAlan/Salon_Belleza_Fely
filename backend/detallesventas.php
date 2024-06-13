<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerDetallesVentas') {
    obtenerDetallesVentas();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearDetalleVenta') {
    crearDetalleVenta();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarDetalleVenta') {
    eliminarDetalleVenta();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarDetalleVenta') {
    actualizarDetalleVenta();
}

function obtenerDetallesVentas() {
    global $conn;

    $sql = "SELECT * FROM detallesventa";
    $result = $conn->query($sql);

    $detallesVentas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $detallesVentas[] = $row;
        }
    }
    echo json_encode($detallesVentas);
}

function crearDetalleVenta() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_venta']) || !isset($data['id_producto']) || !isset($data['cantidad']) || !isset($data['precio_unitario'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO detallesventa (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $data['id_venta'], $data['id_producto'], $data['cantidad'], $data['precio_unitario']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Detalle de venta creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el detalle de venta: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarDetalleVenta() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_detalle'])) {
        echo json_encode(['error' => 'ID de detalle de venta no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM detallesventa WHERE id_detalle = ?");
    $stmt->bind_param("i", $data['id_detalle']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Detalle de venta eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el detalle de venta: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarDetalleVenta() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_detalle']) || !isset($data['id_venta']) || !isset($data['id_producto']) || !isset($data['cantidad']) || !isset($data['precio_unitario'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE detallesventa SET id_venta=?, id_producto=?, cantidad=?, precio_unitario=? WHERE id_detalle=?");
    $stmt->bind_param("iiidi", $data['id_venta'], $data['id_producto'], $data['cantidad'], $data['precio_unitario'], $data['id_detalle']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Detalle de venta actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el detalle de venta: ' . $conn->error]);
    }
    $stmt->close();
}
?>
