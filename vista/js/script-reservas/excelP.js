function descargarExcelReservas(blob, nombreArchivo) {
    if (typeof saveAs === 'function') {
        saveAs(blob, nombreArchivo);
        return;
    }

    const url = window.URL.createObjectURL(blob);
    const enlace = document.createElement('a');
    enlace.href = url;
    enlace.download = nombreArchivo;
    document.body.appendChild(enlace);
    enlace.click();
    enlace.remove();
    window.URL.revokeObjectURL(url);
}

document.addEventListener('click', async (event) => {
    const botonExportar = event.target.closest('#exportar');
    if (!botonExportar) {
        return;
    }

    if (typeof ExcelJS === 'undefined') {
        alert('No se pudo cargar la libreria de Excel.');
        return;
    }

    await new Promise(resolve => setTimeout(resolve, 100));

    const workbook = new ExcelJS.Workbook();
    workbook.creator = 'Ng Wai Foong';
    workbook.lastModifiedBy = 'Bot';
    workbook.created = new Date(2021, 8, 30);
    workbook.modified = new Date();
    workbook.lastPrinted = new Date(2021, 7, 27);

    const worksheet = workbook.addWorksheet('Reservas');

    worksheet.columns = [
        { header: 'ID', key: 'id_auto', width: 10 },
        { header: 'Hora Inicio', key: 'hora_i', width: 15 },
        { header: 'Hora Fin', key: 'hora_f', width: 15 },
        { header: 'Observacion', key: 'observacion', width: 30 },
        { header: 'Insumos', key: 'insumo', width: 30 },
        { header: 'Sala', key: 'nom_sala', width: 20 },
        { header: 'Email', key: 'email', width: 30 }
    ];

    $.ajax({
        url: 'Controlador/Reservas/ObtenerExcel.php',
        type: 'GET',
        data: { centro: 'primaria' },
        dataType: 'json',
        success: async function (data) {
            if (!Array.isArray(data) || data.length === 0) {
                alert('No hay datos para exportar.');
                return;
            }

            data.forEach(reserva => {
                worksheet.addRow(reserva);
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/octet-stream' });
            descargarExcelReservas(blob, 'ReservasPrimaria.xlsx');
        },
        error: function (xhr) {
            console.error('Error al obtener datos:', xhr.responseText);
            alert('Hubo un problema al exportar las reservas.');
        }
    });
});
