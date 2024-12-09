
document.addEventListener('DOMContentLoaded', function () {
    const agregarButtons = document.querySelectorAll('.agregar');
    const detallesTable = document.querySelector('#detalles tbody');
    const totalAPagar = document.querySelector('#total-a-pagar');
    const botonalerta = document.getElementById('botonAlerta');
    const mensajealerta = document.getElementById('errorMensaje');


    let total = 0.00;
    const ids = [];
    let agregados = [];
    let pos = 0;
    agregarButtons.forEach(button => {
        const fila = button.closest('tr');
        ids.push(fila.querySelector('td:nth-child(1)').innerText);
        button.addEventListener('click', function () {
            const row = button.closest('tr');
            const codigo = row.querySelector('td:nth-child(1)').innerText;
            //comprobar si el codigo es el que debe ser agregado (orden)
            if (codigo != ids[pos]) {
                mensajealerta.textContent = 'Debe seleccionar los pagos en orden.';
                botonalerta.click();
                return;
            }
            const concepto = row.querySelector('td:nth-child(2)').innerText;
            const montoAPagarInput = row.querySelector('.monto-a-pagar');
            const montoAPagar = parseFloat(montoAPagarInput.value) || 0.00;

            // Obtener la celda con la clase 'deuda' dentro de la misma fila
            const deudaCell = row.querySelector('.deuda');

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
                        <input type="hidden" name="pagos[${codigo}][monto]" value="${montoAPagar.toFixed(2)}">
                    </td>
                `;
                detallesTable.appendChild(newRow);
                pos++;
                console.log(pos);
                agregados.push(codigo);
                row.classList.add('hidden');
                const eliminarButton = newRow.querySelector('.eliminar');
                eliminarButton.addEventListener('click', function () {
                    if (codigo == agregados[pos - 1]) {
                        agregados.pop();
                        pos--;
                        row.classList.remove('hidden');
                        total -= montoAPagar;
                        totalAPagar.innerText = total.toFixed(2);
                        newRow.remove();
                        // Eliminar el código de la lista de códigos agregados si sigue el orden
                    } else {
                        mensajealerta.textContent = 'Debe eliminar los pagos en orden.';
                        botonalerta.click();
                        return;

                    }
                });

                montoAPagarInput.value = '';
            } else {
                // alert('Ingrese un monto mayor a cero y menor al monto total');
                mensajealerta.textContent = 'El monto a pagar no puede ser 0 ni mayor a la deuda.';
                botonalerta.click();

                if (montoAPagarInput) {
                    montoAPagarInput.value = '';
                }
            }
        });
    });
});