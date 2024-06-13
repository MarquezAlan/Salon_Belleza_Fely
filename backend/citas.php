<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerCitas') {
    obtenerCitas();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearCita') {
    crearCita();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarCita') {
    eliminarCita();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarCita') {
    actualizarCita();
}

function obtenerCitas() {
    global $conn;

    $sql = "SELECT * FROM citas";
    $result = $conn->query($sql);

    $citas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row;
        }
    }
    echo json_encode($citas);
}

function crearCita() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_usuario']) || !isset($data['fecha']) || !isset($data['hora']) || !isset($data['id_servicio']) || !isset($data['estado'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $estado = $data['estado'];
    if ($estado !== 'pendiente' && $estado !== 'realizada') {
        echo json_encode(['error' => 'El estado no es válido']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO citas (id_usuario, fecha, hora, id_servicio, estado) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $data['id_usuario'], $data['fecha'], $data['hora'], $data['id_servicio'], $data['estado']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Cita creada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear la cita: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarCita() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_cita'])) {
        echo json_encode(['error' => 'ID de cita no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM citas WHERE id_cita = ?");
    $stmt->bind_param("i", $data['id_cita']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Cita eliminada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar la cita: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarCita() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_cita']) || !isset($data['id_usuario']) || !isset($data['fecha']) || !isset($data['hora']) || !isset($data['id_servicio']) || !isset($data['estado'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $estado = $data['estado'];
    if ($estado !== 'pendiente' && $estado !== 'realizada') {
        echo json_encode(['error' => 'El estado no es válido']);
        return;
    }

    $stmt = $conn->prepare("UPDATE citas SET id_usuario=?, fecha=?, hora=?, id_servicio=?, estado=? WHERE id_cita=?");
    $stmt->bind_param("issssi", $data['id_usuario'], $data['fecha'], $data['hora'], $data['id_servicio'], $data['estado'], $data['id_cita']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Cita actualizada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar la cita: ' . $conn->error]);
    }
    $stmt->close();
}
?>
