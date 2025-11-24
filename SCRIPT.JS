

/**
 * FUNC 1: Validar que los campos del formulario no ESTEN VACIOS
 * Se ejecuta al enviar el formulario 
 */
function validarFormulario(formulario) {
    const campos = formulario.querySelectorAll('input[required], select[required]');
    let esValido = true;
    
    // Remover alertas anteriores
    const alertaAnterior = document.querySelector('.alert');
    if (alertaAnterior) {
        alertaAnterior.remove();
    }
    
    campos.forEach(campo => {
        if (!campo.value.trim()) {
            esValido = false;
            campo.style.borderColor = '#ef4444';
        } else {
            campo.style.borderColor = '#e5e7eb';
        }
    });
    
    if (!esValido) {
        mostrarAlerta('Por favor, complete todos los campos obligatorios.', 'error');
        return false;
    }
    
    return true;
}

/**
 * FUNC 2: Convertir texto a MAYUS antes de guardarlo
 * Se ejecuta al perder el foco del campo 
 */
function convertirMayusculas(campo) {
    if (campo.value) {
        campo.value = campo.value.toUpperCase();
    }
}

/**
 * FUNC 3: Contar caracteres de un campo
 * Se ejecuta mientras se escribe 
 */
function contarCaracteres(campo, contadorId) {
    const contador = document.getElementById(contadorId);
    const longitud = campo.value.length;
    
    if (contador) {
        contador.textContent = `${longitud} caracteres`;
        
        // Cambiar color según la longitud
        if (longitud > 50) {
            contador.style.color = '#ef4444'; // Rojo si es muy largo
        } else if (longitud > 30) {
            contador.style.color = '#f59e0b'; // Amarillo si es largo
        } else {
            contador.style.color = '#6b7280'; // Gris normal
        }
    }
}


// FUNCIONES CRUD


/**
 * Obtener todos los sensores del localStorage
 */
function obtenerSensores() {
    const sensores = localStorage.getItem('sensores');
    return sensores ? JSON.parse(sensores) : [];
}

/**
 * Guardar sensores en localStorage
 */
function guardarSensores(sensores) {
    localStorage.setItem('sensores', JSON.stringify(sensores));
}

/**
 * Generar ID para nuevos sensores
 */
function generarId() {
    const sensores = obtenerSensores();
    return sensores.length > 0 ? Math.max(...sensores.map(s => s.id)) + 1 : 1;
}

/**
 * CREATE: Crear nuevo SENSOR
 */
function crearSensor() {
    const formulario = document.getElementById('form-crear');
    const formData = new FormData(formulario);
    
    const nuevoSensor = {
        id: generarId(),
        nombre: formData.get('nombre'),
        tipo: formData.get('tipo'),
        ubicacion: formData.get('ubicacion'),
        estado: formData.get('estado'),
        valor_actual: parseFloat(formData.get('valor_actual')),
        fecha_instalacion: formData.get('fecha_instalacion')
    };
    
    const sensores = obtenerSensores();
    sensores.push(nuevoSensor);
    guardarSensores(sensores);
    
    mostrarAlerta('Sensor creado exitosamente.', 'success');
    
    // Redirigir ALOS  2 segundos
    setTimeout(() => {
        window.location.href = 'index.html';
    }, 2000);
}

/**
 * READ: Cargar y mostrar todos los sensores
 */
function cargarSensores() {
    const sensores = obtenerSensores();
    const tbody = document.getElementById('tbody-sensores');
    const totalSensores = document.getElementById('total-sensores');
    const mensajeVacio = document.getElementById('mensaje-vacio');
    const tabla = document.getElementById('tabla-sensores');
    
    if (!tbody) return;
    
    // Actualizar contador
    if (totalSensores) {
        totalSensores.textContent = `${sensores.length} sensor${sensores.length !== 1 ? 'es' : ''}`;
    }
    
    if (sensores.length === 0) {
        if (tabla) tabla.style.display = 'none';
        if (mensajeVacio) mensajeVacio.style.display = 'block';
        return;
    }
    
    if (tabla) tabla.style.display = 'table';
    if (mensajeVacio) mensajeVacio.style.display = 'none';
    
    tbody.innerHTML = '';
    
    sensores.forEach(sensor => {
        const fila = document.createElement('tr');
        
        const estadoClase = `estado-${sensor.estado.toLowerCase().replace('ó', 'o')}`;
        
        fila.innerHTML = `
            <td>${sensor.id}</td>
            <td>${sensor.nombre}</td>
            <td>${sensor.tipo}</td>
            <td>${sensor.ubicacion}</td>
            <td><span class="${estadoClase}">${sensor.estado}</span></td>
            <td>${sensor.valor_actual}</td>
            <td>${formatearFecha(sensor.fecha_instalacion)}</td>
            <td>
                <a href="editar.html?id=${sensor.id}" class="btn btn-warning btn-small">Editar</a>
                <button onclick="eliminarSensor(${sensor.id})" class="btn btn-danger btn-small">Eliminar</button>
            </td>
        `;
        
        tbody.appendChild(fila);
    });
}

/**
 * UPDATE: Cargar datos del sensor para editar
 */
function cargarDatosSensor() {
    const urlParams = new URLSearchParams(window.location.search);
    const sensorId = parseInt(urlParams.get('id'));
    
    if (!sensorId) {
        mostrarAlerta('ID de sensor no válido.', 'error');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 2000);
        return;
    }
    
    const sensores = obtenerSensores();
    const sensor = sensores.find(s => s.id === sensorId);
    
    if (!sensor) {
        mostrarAlerta('Sensor no encontrado.', 'error');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 2000);
        return;
    }
    
    // Llenar el formulario con los datos del sensor
    document.getElementById('sensor-id').value = sensor.id;
    document.getElementById('nombre').value = sensor.nombre;
    document.getElementById('tipo').value = sensor.tipo;
    document.getElementById('ubicacion').value = sensor.ubicacion;
    document.getElementById('estado').value = sensor.estado;
    document.getElementById('valor_actual').value = sensor.valor_actual;
    document.getElementById('fecha_instalacion').value = sensor.fecha_instalacion;
    
    // Actualizar contadores de caracteres
    contarCaracteres(document.getElementById('nombre'), 'contador-nombre');
    contarCaracteres(document.getElementById('ubicacion'), 'contador-ubicacion');
}

/**
 * UPDATE: Actualizar sensor existente
 */
function actualizarSensor() {
    const formulario = document.getElementById('form-editar');
    const formData = new FormData(formulario);
    const sensorId = parseInt(formData.get('id'));
    
    const sensorActualizado = {
        id: sensorId,
        nombre: formData.get('nombre'),
        tipo: formData.get('tipo'),
        ubicacion: formData.get('ubicacion'),
        estado: formData.get('estado'),
        valor_actual: parseFloat(formData.get('valor_actual')),
        fecha_instalacion: formData.get('fecha_instalacion')
    };
    
    const sensores = obtenerSensores();
    const indice = sensores.findIndex(s => s.id === sensorId);
    
    if (indice !== -1) {
        sensores[indice] = sensorActualizado;
        guardarSensores(sensores);
        
        mostrarAlerta('Sensor actualizado exitosamente.', 'success');
        
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 2000);
    } else {
        mostrarAlerta('Error al actualizar el sensor.', 'error');
    }
}

/**
 * DELETE: Eliminar sensor
 */
function eliminarSensor(id) {
    if (confirm('¿Está seguro de que desea eliminar este sensor?')) {
        const sensores = obtenerSensores();
        const sensoresFiltrados = sensores.filter(s => s.id !== id);
        
        guardarSensores(sensoresFiltrados);
        mostrarAlerta('Sensor eliminado exitosamente.', 'success');
        
        // Recargar la lista de sensores
        setTimeout(() => {
            cargarSensores();
        }, 1000);
    }
}


// FUNCIONES AUXILIARES

/**
 * Mostrar alertas al usuario
 */
function mostrarAlerta(mensaje, tipo) {
    // Remover alerta anterior si existe
    const alertaAnterior = document.querySelector('.alert');
    if (alertaAnterior) {
        alertaAnterior.remove();
    }
    
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo}`;
    alerta.textContent = mensaje;
    
    // Insertar la alerta al inicio del main
    const main = document.querySelector('main');
    if (main) {
        main.insertBefore(alerta, main.firstChild);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.remove();
            }
        }, 5000);
    }
}

/**
 * Formatear fecha para mostrar
 */
function formatearFecha(fecha) {
    const opciones = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

/**
 * Inicializar datos de ejemplo (solo si no hay datos)
 */
function inicializarDatosEjemplo() {
    const sensores = obtenerSensores();
    
    if (sensores.length === 0) {
        const sensoresEjemplo = [
            {
                id: 1,
                nombre: "SENSOR TEMPERATURA SALA 1",
                tipo: "Temperatura",
                ubicacion: "SALA PRINCIPAL",
                estado: "Activo",
                valor_actual: 22.5,
                fecha_instalacion: "2024-01-15"
            },
            {
                id: 2,
                nombre: "SENSOR HUMEDAD ALMACÉN",
                tipo: "Humedad",
                ubicacion: "ALMACÉN NORTE",
                estado: "Activo",
                valor_actual: 65.2,
                fecha_instalacion: "2024-02-10"
            },
            {
                id: 3,
                nombre: "SENSOR MOVIMIENTO ENTRADA",
                tipo: "Movimiento",
                ubicacion: "ENTRADA PRINCIPAL",
                estado: "Mantenimiento",
                valor_actual: 0,
                fecha_instalacion: "2024-01-20"
            }
        ];
        
        guardarSensores(sensoresEjemplo);
    }
}

// Inicializar datos de ejemplo al cargar la PAGINA
document.addEventListener('DOMContentLoaded', function() {
    inicializarDatosEjemplo();
});