<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerPagos') {
    obtenerPagos();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearPago') {
    crearPago();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarPago') {
    eliminarPago();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarPago') {
    actualizarPago();
}

function obtenerPagos() {
    global $conn;

    $sql = "SELECT * FROM pagos";
    $result = $conn->query($sql);

    $pagos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pagos[] = $row;
        }
    }
    echo json_encode($pagos);
}

function crearPago() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_venta']) || !isset($data['fecha']) || !isset($data['monto']) || !isset($data['metodo_pago'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO pagos (id_venta, fecha, monto, metodo_pago) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $data['id_venta'], $data['fecha'], $data['monto'], $data['metodo_pago']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Pago creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el pago: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarPago() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_pago'])) {
        echo json_encode(['error' => 'ID de pago no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM pagos WHERE id_pago = ?");
    $stmt->bind_param("i", $data['id_pago']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Pago eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el pago: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarPago() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_pago']) || !isset($data['id_venta']) || !isset($data['fecha']) || !isset($data['monto']) || !isset($data['metodo_pago'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE pagos SET id_venta=?, fecha=?, monto=?, metodo_pago=? WHERE id_pago=?");
    $stmt->bind_param("isdsi", $data['id_venta'], $data['fecha'], $data['monto'], $data['metodo_pago'], $data['id_pago']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Pago actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el pago: ' . $conn->error]);
    }
    $stmt->close();
}
?>
