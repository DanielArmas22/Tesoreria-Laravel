document.addEventListener('DOMContentLoaded', function () {
    const botonesSeleccionar = document.querySelectorAll('.paraCondonar');
    const tablaCondonaciones = document.querySelector('#detalleCon tbody');
    const totalAPagar = document.querySelector('#total-a-condonar');
    // const alerta = document.getElementById('alertaaqui');
    const botonalerta = document.getElementById('botonAlerta');
    const mensajealerta = document.getElementById('errorMensaje');
    let total = 0.00;
    const ids = [];
    let agregados = [];
    let pos = 0;
    botonesSeleccionar.forEach(button => {
        const fila = button.closest('tr');
        ids.push(fila.querySelector('td:nth-child(1)').innerText);
        button.addEventListener('click', function () {
            const row = button.closest('tr');
            const codigo = row.querySelector('td:nth-child(1)').innerText;
            if (codigo != ids[pos]) {
                mensajealerta.textContent = 'Debe seleccionar las Deudas en orden.';
                botonalerta.click();
                return;
            }

            const concepto = row.querySelector('td:nth-child(2)').innerText;
            const montoAPagarInput = row.querySelector('.monto-a-condonar');
            const montoAPagar = parseFloat(montoAPagarInput.value) || 0.00;

            // Obtener la celda con la clase 'deuda' dentro de la misma fila
            const deudaCell = row.querySelector('.deudaMonto');

            // Obtener el contenido de la celda y convertirlo a float
            const deudaValue = parseFloat(deudaCell.textContent.trim());

            if ((montoAPagar > 0 && montoAPagar <= deudaValue)) {
                total += montoAPagar;
                totalAPagar.innerText = total.toFixed(2);

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${codigo}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${concepto}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${montoAPagar.toFixed(2)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" class="eliminar inline-flex items-center px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                            Eliminar
                        </button>
                        <input type="hidden" name="condonaciones[${codigo}][monto]" value="${montoAPagar.toFixed(2)}">
                    </td>
                `;

                tablaCondonaciones.appendChild(newRow);
                //esto oculta la fila de la tabla de deudas seleccionada
                pos++;
                agregados.push(codigo);
                row.classList.add('hidden');


                const eliminarButton = newRow.querySelector('.eliminar');
                eliminarButton.addEventListener('click', function () {
                    if (codigo == agregados[pos - 1]) {
                        agregados.pop();
                        pos--;
                        total -= montoAPagar;
                        totalAPagar.innerText = total.toFixed(2);
                        newRow.remove();
                        //esto muestra  la fila de la tabla de deudas seleccionada
                        row.classList.remove('hidden');
                    } else {
                        mensajealerta.textContent = 'Debe eliminar  los Deudas en orden.';
                        botonalerta.click();
                    }

                });

                montoAPagarInput.value = '';
            } else {
                if (montoAPagar > deudaValue) {
                    // alert('El monto a condonar no puede ser mayor a la deuda.');
                    // mensaje= .innerHTML = '<strong>El monto a condonar no puede ser mayor a la deuda.</strong>';
                    mensajealerta.textContent = 'El monto a condonar no puede ser mayor a la deuda.';
                    botonalerta.click();

                } else {
                    // alert('El monto a condonar debe ser mayor a cero.');
                    mensajealerta.textContent = 'El monto a condonar debe ser mayor a cero.';
                    botonalerta.click();

                }
                // console.log('montoAPagar:', montoAPagar);
                if (montoAPagarInput) {
                    montoAPagarInput.value = '';
                }
            }

        });
    });
});