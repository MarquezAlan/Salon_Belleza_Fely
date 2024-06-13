<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerServicios') {
    obtenerServicios();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearServicio') {
    crearServicio();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarServicio') {
    eliminarServicio();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarServicio') {
    actualizarServicio();
}

function obtenerServicios() {
    global $conn;

    $sql = "SELECT * FROM servicios";
    $result = $conn->query($sql);

    $servicios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }
    }
    echo json_encode($servicios);
}

function crearServicio() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre']) || !isset($data['descripcion']) || !isset($data['precio'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO servicios (nombre, descripcion, precio) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $data['nombre'], $data['descripcion'], $data['precio']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Servicio creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el servicio: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarServicio() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_servicio'])) {
        echo json_encode(['error' => 'ID de servicio no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM servicios WHERE id_servicio = ?");
    $stmt->bind_param("i", $data['id_servicio']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Servicio eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el servicio: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarServicio() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_servicio']) || !isset($data['nombre']) || !isset($data['descripcion']) || !isset($data['precio'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE servicios SET nombre=?, descripcion=?, precio=? WHERE id_servicio=?");
    $stmt->bind_param("ssdi", $data['nombre'], $data['descripcion'], $data['precio'], $data['id_servicio']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Servicio actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el servicio: ' . $conn->error]);
    }
    $stmt->close();
}
?>
