<?php

// CONF DE CNX A BASE DE DATOS



// CONF de la base de datos
define('DB_HOST', 'localhost');        // Servidor de base de datos
define('DB_USER', 'root');             // Usuario de MySQL 
define('DB_PASS', '');                 // Contraseña de MySQL 
define('DB_NAME', 'examen_prog');      // Nombre de la base de datos

// FNC para conectar a la base de datos
function conectarDB() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// FNC para mostrar mensajes de ERROR EXITO
function mostrarMensaje($mensaje, $tipo = 'info') {
    $clase = '';
    switch ($tipo) {
        case 'success':
            $clase = 'alert-success';
            break;
        case 'error':
            $clase = 'alert-error';
            break;
        default:
            $clase = 'alert-info';
    }
    
    echo "<div class='alert $clase'>$mensaje</div>";
}

// FNC para validar datos de entrada
function validarDatos($datos) {
    $errores = [];
    
    if (empty($datos['nombre'])) {
        $errores[] = "El nombre del sensor es obligatorio";
    }
    
    if (empty($datos['tipo'])) {
        $errores[] = "El tipo de sensor es obligatorio";
    }
    
    if (empty($datos['ubicacion'])) {
        $errores[] = "La ubicación es obligatoria";
    }
    
    if (empty($datos['estado'])) {
        $errores[] = "El estado es obligatorio";
    }
    
    if (!isset($datos['valor_actual']) || $datos['valor_actual'] === '') {
        $errores[] = "El valor actual es obligatorio";
    }
    
    if (empty($datos['fecha_instalacion'])) {
        $errores[] = "La fecha de instalación es obligatoria";
    }
    
    return $errores;
}

// FNC para limpiar datos de entrada
function limpiarDatos($datos) {
    return [
        'nombre' => strtoupper(trim($datos['nombre'])),
        'tipo' => trim($datos['tipo']),
        'ubicacion' => strtoupper(trim($datos['ubicacion'])),
        'estado' => trim($datos['estado']),
        'valor_actual' => floatval($datos['valor_actual']),
        'fecha_instalacion' => trim($datos['fecha_instalacion'])
    ];
}
?>