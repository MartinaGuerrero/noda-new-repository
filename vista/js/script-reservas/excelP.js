document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('exportar').addEventListener('click', async () => {
        console.log("Botón exportar clicado");

        // Agregar un pequeño retraso
        await new Promise(resolve => setTimeout(resolve, 100));

        // Crear un nuevo libro de trabajo
        const workbook = new ExcelJS.Workbook();
        workbook.creator = 'Ng Wai Foong';
        workbook.lastModifiedBy = 'Bot';
        workbook.created = new Date(2021, 8, 30);
        workbook.modified = new Date();
        workbook.lastPrinted = new Date(2021, 7, 27);

        const worksheet = workbook.addWorksheet('New Sheet');

        // Definir las columnas
        worksheet.columns = [
            { header: 'ID', key: 'id_auto', width: 10 },
            { header: 'Hora Inicio', key: 'hora_i', width: 15 },
            { header: 'Hora Fin', key: 'hora_f', width: 15 },
            { header: 'Observación', key: 'observacion', width: 30 },
            { header: 'Insumos', key: 'insumo', width: 30 },
            { header: 'Sala', key: 'nom_sala', width: 20 },
            { header: 'Email', key: 'email', width: 30 }
        ];

        // Hacer una solicitud AJAX al archivo PHP
        $.ajax({
            url: 'modelo/obtenerExcelP.php',
            type: 'GET',
            dataType: 'json',
            success: async function(data) {
                console.log("Datos recibidos:", data);
                if (!data || data.length === 0) {
                    console.warn("No se recibieron datos válidos.");
                    return;
                }
                const reservasArray = data;
                reservasArray.forEach(reserva => {
                    // Verificar que el campo 'insumo' esté presente
                    if (!reserva.hasOwnProperty('insumo')) {
                        console.warn("Falta el campo 'insumo' en la reserva:", reserva);
                    }
                    console.log("Fila a agregar:", reserva); // Verificar cada reserva
                    worksheet.addRow(reserva);
                });

                // Guardar el archivo
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/octet-stream' });
                saveAs(blob, 'ReservasPrimaria.xlsx');
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener datos:', xhr.responseText);
            }
        });
    });
});