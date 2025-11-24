<?php

// PROCESAMIENTO DE FORMULARIOS


require_once 'config.php';
require_once 'funciones.php';

// Verificar que  sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index_php.php');
    exit;
}

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'crear':
        procesarCreacion();
        break;
    case 'actualizar':
        procesarActualizacion();
        break;
    case 'eliminar':
        procesarEliminacion();
        break;
    default:
        header('Location: index_php.php');
        exit;
}


// Procesar CREACION de nuevo sensor

function procesarCreacion() {
    // Validar datos
    $errores = validarDatos($_POST);
    
    if (!empty($errores)) {
        $mensaje = "Errores encontrados: " . implode(", ", $errores);
        header("Location: crear_php.php?error=" . urlencode($mensaje));
        exit;
    }
    
    // Limpiar datos
    $datos = limpiarDatos($_POST);
    
    // Crear sensor
    if (crearSensor($datos)) {
        $mensaje = "Sensor creado exitosamente";
        header("Location: index_php.php?success=" . urlencode($mensaje));
    } else {
        $mensaje = "Error al crear el sensor";
        header("Location: crear_php.php?error=" . urlencode($mensaje));
    }
    exit;
}


// Procesar ACTUALIZACION de sensor

function procesarActualizacion() {
    $id = $_POST['id'] ?? 0;
    
    if (!$id) {
        header("Location: index_php.php?error=" . urlencode("ID de sensor no válido"));
        exit;
    }
    
    // Validar datos
    $errores = validarDatos($_POST);
    
    if (!empty($errores)) {
        $mensaje = "Errores encontrados: " . implode(", ", $errores);
        header("Location: editar_php.php?id=$id&error=" . urlencode($mensaje));
        exit;
    }
    
    // Limpiar datos
    $datos = limpiarDatos($_POST);
    
    // Actualizar sensor
    if (actualizarSensor($id, $datos)) {
        $mensaje = "Sensor actualizado exitosamente";
        header("Location: index_php.php?success=" . urlencode($mensaje));
    } else {
        $mensaje = "Error al actualizar el sensor";
        header("Location: editar_php.php?id=$id&error=" . urlencode($mensaje));
    }
    exit;
}


// Procesar eliminación de sensor

function procesarEliminacion() {
    $id = $_POST['id'] ?? 0;
    
    if (!$id) {
        header("Location: index_php.php?error=" . urlencode("ID de sensor no válido"));
        exit;
    }
    
    // Verificar que el sensor existe
    $sensor = obtenerSensorPorId($id);
    if (!$sensor) {
        header("Location: index_php.php?error=" . urlencode("Sensor no encontrado"));
        exit;
    }
    
    // Eliminar sensor
    if (eliminarSensor($id)) {
        $mensaje = "Sensor eliminado exitosamente";
        header("Location: index_php.php?success=" . urlencode($mensaje));
    } else {
        $mensaje = "Error al eliminar el sensor";
        header("Location: index_php.php?error=" . urlencode($mensaje));
    }
    exit;
}
?>