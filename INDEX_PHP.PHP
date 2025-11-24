<?php

// PGN PRINCIPAL - LISTADO DE SENSORES (PHP)


require_once 'config.php';
require_once 'funciones.php';

// Obtener todos los sensores
$sensores = obtenerSensores();
$totalSensores = contarSensores();

// Manejar mensajes de EXITO O ERROR
$mensaje = '';
$tipoMensaje = '';

if (isset($_GET['success'])) {
    $mensaje = $_GET['success'];
    $tipoMensaje = 'success';
} elseif (isset($_GET['error'])) {
    $mensaje = $_GET['error'];
    $tipoMensaje = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Sensores IoT - PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestor de Sensores IoT</h1>
            <p>Sistema de administración con PHP y MySQL</p>
        </header>

        <nav class="navigation">
            <a href="index_php.php" class="btn btn-primary active">Ver Sensores (PHP)</a>
            <a href="crear_php.php" class="btn btn-success">Agregar Sensor</a>
            <a href="index.html" class="btn btn-secondary">Versión JavaScript</a>
        </nav>

        <main>
            <?php if ($mensaje): ?>
                <?php mostrarMensaje($mensaje, $tipoMensaje); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h2>Lista de Sensores</h2>
                    <span class="badge"><?php echo $totalSensores; ?> sensor<?php echo $totalSensores != 1 ? 'es' : ''; ?></span>
                </div>
                
                <?php if (empty($sensores)): ?>
                    <div class="mensaje-vacio">
                        <p>No hay sensores registrados en la base de datos.</p>
                        <a href="crear_php.php" class="btn btn-success">Agregar el primer sensor</a>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Valor Actual</th>
                                    <th>Fecha Instalación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sensores as $sensor): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sensor['id']); ?></td>
                                        <td><?php echo htmlspecialchars($sensor['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($sensor['tipo']); ?></td>
                                        <td><?php echo htmlspecialchars($sensor['ubicacion']); ?></td>
                                        <td>
                                            <span class="<?php echo obtenerClaseEstado($sensor['estado']); ?>">
                                                <?php echo htmlspecialchars($sensor['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($sensor['valor_actual'], 2); ?></td>
                                        <td><?php echo formatearFecha($sensor['fecha_instalacion']); ?></td>
                                        <td>
                                            <a href="editar_php.php?id=<?php echo $sensor['id']; ?>" class="btn btn-warning btn-small">Editar</a>
                                            <form style="display: inline;" method="POST" action="procesar.php" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar este sensor?');">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <input type="hidden" name="id" value="<?php echo $sensor['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>

    
    </div>

    <script src="script.js"></script>
</body>
</html>