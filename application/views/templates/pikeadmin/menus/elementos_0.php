<script>
    var cf_indices = {
        usuarios_explorar: [0,-1],
        usuarios_nuevo: [0,-1],
        usuarios_importar: [0,-1],
        admin_acl: [2,0],
        items_listado: [2,1],
        lugares_sublugares: [2,2]
    };
    
    var elementos_sidebar = [
            {
                texto: 'Usuarios',
                activo: false,
                style: '',
                icono: 'fa fa-fw fa-user',
                cf: 'usuarios/explorar',
                submenu: false,
                subelementos: []
            },
            {
                texto: 'Pruebas',
                activo: false,
                style: '',
                icono: 'fa fa-fw fa-pencil-alt',
                cf: 'app/prueba',
                submenu: false,
                subelementos: []
            },
            {
                texto: 'Ajustes',
                activo: false,
                style: '',
                icono: 'fa fa-fw fa-sliders-h',
                cf: '',
                submenu: true,
                subelementos: [
                    {
                        texto: 'Parámetros',
                        activo: false,
                        icono: 'fa fa-fw fa-cogs',
                        cf: 'admin/acl'
                    },
                    {
                        texto: 'Ítems',
                        activo: false,
                        icono: 'fa fa-fw fa-bars',
                        cf: 'items/administrar'
                    },
                    {
                        texto: 'Lugares y ciudades',
                        activo: false,
                        icono: 'fa fa-fw fa-map-marker',
                        cf: 'lugares/sublugares'
                    }
                ]
            }
        ]
</script>