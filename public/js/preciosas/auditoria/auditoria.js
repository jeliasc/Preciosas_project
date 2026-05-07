function verDetalle(id) {
    fetch('/auditoria/' + id)
        .then(res => res.json())
        .then(data => {
            renderAuditoria(data);
            $('#modalDetalle').modal('show');
        });
}

function renderAuditoria(data) {
    let antes = data.anteriores || {};
    let despues = data.nuevos || {};

    const ignorar = ['id', 'created_at', 'updated_at'];

    const aliasCampos = {
        role_id: 'Rol',
        permission_id: 'Permiso',
        user_id: 'Usuario',
        proveedor_id: 'Proveedor',
        cliente_id: 'Cliente',
        categoria_id: 'Categoría',
        estado_id: 'Estado',
        name: 'Nombre',
        email: 'Correo',
        no_factura: 'No. Factura',
        numero_factura: 'No. Factura',
        fecha: 'Fecha',
        tax: 'Impuesto',
        total: 'Total',
        precio: 'Precio',
        stock: 'Stock',
        nombre: 'Nombre',
        code: 'Código',
        descripcion: 'Descripción'
    };

    let campos = new Set([
        ...Object.keys(antes),
        ...Object.keys(despues)
    ]);

    let html = `
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Antes</th>
                    <th>Después</th>
                </tr>
            </thead>
            <tbody>
    `;

    let huboCambios = false;

    campos.forEach(campo => {
        if (ignorar.includes(campo)) return;

        let valAntes = antes[campo] ?? '-';
        let valDespues = despues[campo] ?? '-';

        if (String(valAntes) !== String(valDespues)) {
            huboCambios = true;

            html += `
                <tr>
                    <td><strong>${aliasCampos[campo] || campo}</strong></td>
                    <td>${valAntes}</td>
                    <td>${valDespues}</td>
                </tr>
            `;
        }
    });

    if (!huboCambios) {
        html += `
            <tr>
                <td colspan="3" class="text-center">
                    No hubo cambios visibles en los campos principales.
                </td>
            </tr>
        `;
    }

    html += `
            </tbody>
        </table>
    `;

    document.getElementById('detalleAuditoria').innerHTML = html;
}

 function cerrarModalAuditoria() {
            $('#modalDetalle').modal('hide');
        }