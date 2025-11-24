<?php

// FUNCIONES CRUD PARA SENSORES


require_once 'config.php';


// CREATE - Crear nuevo sensor

function crearSensor($datos) {
    try {
        $pdo = conectarDB();
        
        $sql = "INSERT INTO sensores (nombre, tipo, ubicacion, estado, valor_actual, fecha_instalacion) 
                VALUES (:nombre, :tipo, :ubicacion, :estado, :valor_actual, :fecha_instalacion)";
        
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':tipo' => $datos['tipo'],
            ':ubicacion' => $datos['ubicacion'],
            ':estado' => $datos['estado'],
            ':valor_actual' => $datos['valor_actual'],
            ':fecha_instalacion' => $datos['fecha_instalacion']
        ]);
        
        return $resultado;
    } catch (PDOException $e) {
        error_log("Error al crear sensor: " . $e->getMessage());
        return false;
    }
}


// READ - Obtener todos los sensores

function obtenerSensores() {
    try {
        $pdo = conectarDB();
        
        $sql = "SELECT * FROM sensores ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error al obtener sensores: " . $e->getMessage());
        return [];
    }
}


// READ - Obtener un sensor por ID

function obtenerSensorPorId($id) {
    try {
        $pdo = conectarDB();
        
        $sql = "SELECT * FROM sensores WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error al obtener sensor: " . $e->getMessage());
        return false;
    }
}


// UPDATE - Actualizar sensor existente

function actualizarSensor($id, $datos) {
    try {
        $pdo = conectarDB();
        
        $sql = "UPDATE sensores 
                SET nombre = :nombre, 
                    tipo = :tipo, 
                    ubicacion = :ubicacion, 
                    estado = :estado, 
                    valor_actual = :valor_actual, 
                    fecha_instalacion = :fecha_instalacion 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':tipo' => $datos['tipo'],
            ':ubicacion' => $datos['ubicacion'],
            ':estado' => $datos['estado'],
            ':valor_actual' => $datos['valor_actual'],
            ':fecha_instalacion' => $datos['fecha_instalacion']
        ]);
        
        return $resultado;
    } catch (PDOException $e) {
        error_log("Error al actualizar sensor: " . $e->getMessage());
        return false;
    }
}


// DELETE - Eliminar sensor

function eliminarSensor($id) {
    try {
        $pdo = conectarDB();
        
        $sql = "DELETE FROM sensores WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([':id' => $id]);
        
        return $resultado;
    } catch (PDOException $e) {
        error_log("Error al eliminar sensor: " . $e->getMessage());
        return false;
    }
}


// FNC para contar total de sensores

function contarSensores() {
    try {
        $pdo = conectarDB();
        
        $sql = "SELECT COUNT(*) as total FROM sensores";
        $stmt = $pdo->query($sql);
        $resultado = $stmt->fetch();
        
        return $resultado['total'];
    } catch (PDOException $e) {
        error_log("Error al contar sensores: " . $e->getMessage());
        return 0;
    }
}


// FNC para obtener sensores por estado

function obtenerSensoresPorEstado($estado) {
    try {
        $pdo = conectarDB();
        
        $sql = "SELECT * FROM sensores WHERE estado = :estado ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':estado' => $estado]);
        
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error al obtener sensores por estado: " . $e->getMessage());
        return [];
    }
}


// FNC para formatear fecha

function formatearFecha($fecha) {
    $fechaObj = new DateTime($fecha);
    return $fechaObj->format('d/m/Y');
}


// FNC para obtener clase CSS SEGUN estado

function obtenerClaseEstado($estado) {
    switch (strtolower($estado)) {
        case 'activo':
            return 'estado-activo';
        case 'inactivo':
            return 'estado-inactivo';
        case 'mantenimiento':
            return 'estado-mantenimiento';
        default:
            return '';
    }
}
?>