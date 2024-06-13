<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerAdministradores') {
    obtenerAdministradores();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearAdministrador') {
    crearAdministrador();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarAdministrador') {
    eliminarAdministrador();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarAdministrador') {
    actualizarAdministrador();
}

function obtenerAdministradores() {
    global $conn;

    $sql = "SELECT * FROM administradores";
    $result = $conn->query($sql);

    $administradores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $administradores[] = $row;
        }
    }
    echo json_encode($administradores);
}

function crearAdministrador() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre_usuario']) || !isset($data['contrasenia']) || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['email'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO administradores (nombre_usuario, contrasenia, nombre, apellido, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $data['nombre_usuario'], $data['contrasenia'], $data['nombre'], $data['apellido'], $data['email']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Administrador creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el administrador: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarAdministrador() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_administrador'])) {
        echo json_encode(['error' => 'ID de administrador no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM administradores WHERE id_administrador = ?");
    $stmt->bind_param("i", $data['id_administrador']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Administrador eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el administrador: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarAdministrador() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_administrador']) || !isset($data['nombre_usuario']) || !isset($data['contrasenia']) || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['email'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE administradores SET nombre_usuario=?, contrasenia=?, nombre=?, apellido=?, email=? WHERE id_administrador=?");
    $stmt->bind_param("sssssi", $data['nombre_usuario'], $data['contrasenia'], $data['nombre'], $data['apellido'], $data['email'], $data['id_administrador']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Administrador actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el administrador: ' . $conn->error]);
    }
    $stmt->close();
}
?>
