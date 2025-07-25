/**
 * Script de debug para verificar el funcionamiento de estados
 */

// Función de debug para verificar el estado de los elementos
function debugEstados() {
    console.log('=== DEBUG ESTADOS ===');
    
    const estados = ['futuro1', 'futuro2', 'futuro3', 'futuro4'];
    const hiddenInputs = ['enviar_fecha_entrega', 'enviar_fecha_impresion', 'enviar_fecha_anulado', 'enviar_factura_entrega'];
    
    console.log('Estado seleccionado:', document.getElementById('estado')?.value);
    
    console.log('\nElementos de fecha:');
    estados.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            const isVisible = el.style.display !== 'none' && !el.classList.contains('hidden');
            console.log(`${id}: ${isVisible ? 'VISIBLE' : 'OCULTO'} | style.display: "${el.style.display}" | hidden class: ${el.classList.contains('hidden')}`);
        } else {
            console.log(`${id}: NO ENCONTRADO`);
        }
    });
    
    console.log('\nCampos hidden:');
    hiddenInputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            console.log(`${id}: "${el.value}"`);
        } else {
            console.log(`${id}: NO ENCONTRADO`);
        }
    });
    
    console.log('==================');
}

// Función para probar manualmente los estados
function probarEstado(estado) {
    console.log(`\n=== PROBANDO ESTADO: ${estado} ===`);
    
    // Cambiar el select
    const estadoSelect = document.getElementById('estado');
    if (estadoSelect) {
        estadoSelect.value = estado;
        
        // Disparar el evento change manualmente
        estadoSelect.dispatchEvent(new Event('change'));
        
        // También llamar la función original si existe
        if (typeof mostrarOcultarCampos === 'function') {
            mostrarOcultarCampos(estado);
        }
        
        // Esperar un poco y mostrar debug
        setTimeout(() => {
            debugEstados();
        }, 100);
    } else {
        console.log('ERROR: No se encontró el select de estado');
    }
}

// Función para probar todos los estados automáticamente
function probarTodosLosEstados() {
    const estados = ['PENDIENTE', 'ENTREGADO', 'DESABASTECIDO', 'ANULADO'];
    let index = 0;
    
    function siguiente() {
        if (index < estados.length) {
            probarEstado(estados[index]);
            index++;
            setTimeout(siguiente, 2000); // 2 segundos entre cada prueba
        } else {
            console.log('\n=== PRUEBAS COMPLETADAS ===');
        }
    }
    
    siguiente();
}

// Exponer funciones globalmente para uso en consola
window.debugEstados = debugEstados;
window.probarEstado = probarEstado;
window.probarTodosLosEstados = probarTodosLosEstados;

// Auto-ejecutar debug cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Debug de estados cargado. Funciones disponibles:');
    console.log('- debugEstados(): Muestra el estado actual de todos los elementos');
    console.log('- probarEstado("ENTREGADO"): Prueba un estado específico');
    console.log('- probarTodosLosEstados(): Prueba todos los estados automáticamente');
    
    // Debug inicial después de 1 segundo
    setTimeout(debugEstados, 1000);
});