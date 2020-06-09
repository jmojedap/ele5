<script>
    var nav_1_elements = [
            {
                id: 'nav_1_usuarios',
                text: 'Usuarios',
                active: false,
                style: '',
                icon: 'fa fa-user',
                cf: 'usuarios/explorar',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_institucional',
                text: 'Instituciones',
                active: false,
                style: '',
                icon: 'fa fa-university',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Instituciones',
                        active: false,
                        icon: 'fa fa-university',
                        cf: 'instituciones/explorar'
                    },
                    {
                        text: 'Grupos',
                        active: false,
                        icon: 'fa fa-users',
                        cf: 'grupos/explorar'
                    }
                ]
            },
            {
                id: 'nav_1_recursos',
                text: 'Recursos',
                active: false,
                style: '',
                icon: 'fa fa-book',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Kits',
                        active: false,
                        icon: 'fa fa-suitcase',
                        cf: 'kits/explorar'
                    },
                    {
                        text: 'Programas',
                        active: false,
                        icon: 'fa fa-sitemap',
                        cf: 'programas/explorar'
                    },
                    {
                        text: 'Contenidos',
                        active: false,
                        icon: 'fa fa-book',
                        cf: 'flipbooks/explorar'
                    },
                    {
                        text: 'Temas',
                        active: false,
                        icon: 'fa fa-bars',
                        cf: 'temas/explorar'
                    },
                    {
                        text: 'Contenidos AP',
                        active: false,
                        icon: 'fa fa-book',
                        cf: 'posts/ap_explorar'
                    },
                    {
                        text: 'Quices',
                        active: false,
                        icon: 'fa fa-question',
                        cf: 'quices/explorar'
                    },
                    {
                        text: 'Páginas',
                        active: false,
                        icon: 'far fa-file',
                        cf: 'paginas/explorar'
                    },
                    {
                        text: 'Archivos',
                        active: false,
                        icon: 'far fa-folder',
                        cf: 'recursos/archivos'
                    },
                    {
                        text: 'Links',
                        active: false,
                        icon: 'fa fa-globe',
                        cf: 'recursos/links'
                    },
                ]
            },
            {
                id: 'nav_1_cuestionarios',
                text: 'Cuestionarios',
                active: false,
                style: '',
                icon: 'fa fa-question',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Cuestionarios',
                        active: false,
                        icon: 'fa fa-book',
                        cf: 'cuestionarios/explorar'
                    },
                    {
                        text: 'Lecturas',
                        active: false,
                        icon: 'fa fa-quote-left',
                        cf: 'datos/enunciados'
                    },
                    {
                        text: 'Preguntas',
                        active: false,
                        icon: 'fa fa-question',
                        cf: 'preguntas/explorar'
                    }
                ]
            },
            {
                id: 'nav_1_ajustes',
                text: 'Ajustes',
                active: false,
                style: '',
                icon: 'fa fa-sliders-h',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Parámetros',
                        active: false,
                        icon: 'fa fa-cog',
                        cf: 'datos/sis_opcion'
                    },
                    {
                        text: 'Ítems',
                        active: false,
                        icon: 'fa fa-bars',
                        cf: 'items/listado'
                    },
                    {
                        text: 'Procesos',
                        active: false,
                        icon: 'fa fa-tasks',
                        cf: 'develop/procesos'
                    },
                    {
                        text: 'Base de datos',
                        active: false,
                        icon: 'fa fa-database',
                        cf: 'develop/tablas/item'
                    },
                    {
                        text: 'Bitácora',
                        active: false,
                        icon: 'fa fa-clipboard',
                        cf: 'posts/bitacora'
                    },
                    {
                        text: 'Estadísticas',
                        active: false,
                        icon: 'fa fa-chart-line',
                        cf: 'estadisticas/ctn_correctas_incorrectas'
                    },
                    {
                        text: 'Lugares',
                        active: false,
                        icon: 'fa fa-map-marker-alt',
                        cf: 'lugares/explorar'
                    },
                    {
                        text: 'Ayuda',
                        active: false,
                        icon: 'fa fa-question-circle',
                        cf: 'datos/ayudas_explorar'
                    }
                ]
            },
        ];
</script>