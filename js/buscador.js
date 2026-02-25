// assets/js/buscador.js

document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador');
    
    if (buscador) {
        buscador.addEventListener('keyup', function() {
            let texto = this.value.toLowerCase();
            let filas = document.querySelectorAll('#tablaProductos tbody tr');
            
            filas.forEach(fila => {
                // Ignorar la fila de "No hay productos"
                if(!fila.querySelector('td[colspan]')) {
                    fila.style.display = fila.innerText.toLowerCase().includes(texto) ? '' : 'none';
                }
            });
        });
    }
});
