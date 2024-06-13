<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'obtenerCategorias') {
    obtenerCategorias();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'crearCategoria') {
    crearCategoria();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'eliminarCategoria') {
    eliminarCategoria();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'actualizarCategoria') {
    actualizarCategoria();
}

function obtenerCategorias() {
    global $conn;

    $sql = "SELECT * FROM categorias";
    $result = $conn->query($sql);

    $categorias = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
    echo json_encode($categorias);
}

function crearCategoria() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre']) || !isset($data['descripcion'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['nombre'], $data['descripcion']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Categoría creada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al crear la categoría: ' . $conn->error]);
    }
    $stmt->close();
}

function eliminarCategoria() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_categoria'])) {
        echo json_encode(['error' => 'ID de categoría no especificado']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM categorias WHERE id_categoria = ?");
    $stmt->bind_param("i", $data['id_categoria']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Categoría eliminada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al eliminar la categoría: ' . $conn->error]);
    }
    $stmt->close();
}

function actualizarCategoria() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_categoria']) || !isset($data['nombre']) || !isset($data['descripcion'])) {
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $stmt = $conn->prepare("UPDATE categorias SET nombre=?, descripcion=? WHERE id_categoria=?");
    $stmt->bind_param("ssi", $data['nombre'], $data['descripcion'], $data['id_categoria']);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Categoría actualizada con éxito']);
    } else {
        echo json_encode(['error' => 'Error al actualizar la categoría: ' . $conn->error]);
    }
    $stmt->close();
}
?>
