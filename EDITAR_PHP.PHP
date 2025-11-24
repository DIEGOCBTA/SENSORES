<?php

// PAGINA DE EDITAR DE SENSORES (PHP)

require_once 'config.php';
require_once 'funciones.php';

//  ID del sensor
$id = $_GET['id'] ?? 0;

if (!$id) {
    header("Location: index_php.php?error=" . urlencode("ID de sensor no válido"));
    exit;
}

//  datos del sensor
$sensor = obtenerSensorPorId($id);

if (!$sensor) {
    header("Location: index_php.php?error=" . urlencode("Sensor no encontrado"));
    exit;
}

//  mensajes de error
$mensaje = '';
$tipoMensaje = '';

if (isset($_GET['error'])) {
    $mensaje = $_GET['error'];
    $tipoMensaje = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sensor - Gestor IoT</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestor de Sensores IoT</h1>
            <p>Editar sensor existente</p>
        </header>

        <nav class="navigation">
            <a href="index_php.php" class="btn btn-primary">Ver Sensores</a>
            <a href="crear_php.php" class="btn btn-success">Agregar Sensor</a>
            <a href="index.html" class="btn btn-secondary">Versión JavaScript</a>
        </nav>

        <main>
            <?php if ($mensaje): ?>
                <?php mostrarMensaje($mensaje, $tipoMensaje); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h2>Editar Sensor #<?php echo htmlspecialchars($sensor['id']); ?></h2>
                </div>
                
                <form method="POST" action="procesar.php" class="sensor-form" onsubmit="return validarFormulario(this)">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($sensor['id']); ?>">
                    
                    <div class="form-group">
                        <label for="nombre">Nombre del Sensor: *</label>
                        <input type="text" id="nombre" name="nombre" 
                               value="<?php echo htmlspecialchars($sensor['nombre']); ?>" 
                               required onblur="convertirMayusculas(this)" 
                               oninput="contarCaracteres(this, 'contador-nombre')">
                        <small id="contador-nombre" class="contador">0 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Sensor: *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="Temperatura" <?php echo $sensor['tipo'] == 'Temperatura' ? 'selected' : ''; ?>>Temperatura</option>
                            <option value="Humedad" <?php echo $sensor['tipo'] == 'Humedad' ? 'selected' : ''; ?>>Humedad</option>
                            <option value="Presión" <?php echo $sensor['tipo'] == 'Presión' ? 'selected' : ''; ?>>Presión</option>
                            <option value="Movimiento" <?php echo $sensor['tipo'] == 'Movimiento' ? 'selected' : ''; ?>>Movimiento</option>
                            <option value="Luz" <?php echo $sensor['tipo'] == 'Luz' ? 'selected' : ''; ?>>Luz</option>
                            <option value="Sonido" <?php echo $sensor['tipo'] == 'Sonido' ? 'selected' : ''; ?>>Sonido</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion">Ubicación: *</label>
                        <input type="text" id="ubicacion" name="ubicacion" 
                               value="<?php echo htmlspecialchars($sensor['ubicacion']); ?>" 
                               required onblur="convertirMayusculas(this)"
                               oninput="contarCaracteres(this, 'contador-ubicacion')">
                        <small id="contador-ubicacion" class="contador">0 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado: *</label>
                        <select id="estado" name="estado" required>
                            <option value="">Seleccione un estado</option>
                            <option value="Activo" <?php echo $sensor['estado'] == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                            <option value="Inactivo" <?php echo $sensor['estado'] == 'Inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                            <option value="Mantenimiento" <?php echo $sensor['estado'] == 'Mantenimiento' ? 'selected' : ''; ?>>Mantenimiento</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="valor_actual">Valor Actual: *</label>
                        <input type="number" id="valor_actual" name="valor_actual" 
                               value="<?php echo htmlspecialchars($sensor['valor_actual']); ?>" 
                               step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="fecha_instalacion">Fecha de Instalación: *</label>
                        <input type="date" id="fecha_instalacion" name="fecha_instalacion" 
                               value="<?php echo htmlspecialchars($sensor['fecha_instalacion']); ?>" 
                               required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-warning">Actualizar Sensor</button>
                        <a href="index_php.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>

        <footer>
            <p>&copy; 2024 Gestor de Sensores IoT - Examen de Programación</p>
        </footer>
    </div>

    <script src="script.js"></script>
    <script>
        // Actualizar contadores al cargar
        document.addEventListener('DOMContentLoaded', function() {
            contarCaracteres(document.getElementById('nombre'), 'contador-nombre');
            contarCaracteres(document.getElementById('ubicacion'), 'contador-ubicacion');
        });
    </script>
</body>
</html>