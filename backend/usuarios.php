<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerUsuarios') {
    obtenerUsuarios();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearUsuario') {
    crearUsuario();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarUsuario') {
    eliminarUsuario();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarUsuario') {
    actualizarUsuario();
}

function obtenerUsuarios() {
    global $conn;

    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);

    $usuarios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }
    echo json_encode($usuarios);
}

function crearUsuario() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre_usuario']) || !isset($data['contrasenia']) || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['email']) || !isset($data['telefono']) || !isset($data['direccion'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, contrasenia, nombre, apellido, email, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $data['nombre_usuario'], $data['contrasenia'], $data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['direccion']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Usuario creado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear el usuario: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarUsuario() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_usuario'])) {
        echo json_encode(['error' => 'ID de usuario no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $data['id_usuario']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Usuario eliminado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el usuario: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarUsuario() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_usuario']) || !isset($data['nombre_usuario']) || !isset($data['contrasenia']) || !isset($data['nombre']) || !isset($data['apellido']) || !isset($data['email']) || !isset($data['telefono']) || !isset($data['direccion'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario=?, contrasenia=?, nombre=?, apellido=?, email=?, telefono=?, direccion=? WHERE id_usuario=?");
    $stmt->bind_param("sssssssi", $data['nombre_usuario'], $data['contrasenia'], $data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['direccion'], $data['id_usuario']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Usuario actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar el usuario: ' . $conn->error]);
    }
    $stmt->close();
}
?>
