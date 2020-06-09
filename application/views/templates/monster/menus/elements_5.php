<script>
    var nav_1_elements = [
            {
                id: 'nav_1_inicio',
                text: 'Inicio',
                active: false,
                style: '',
                icon: 'fa fa-home',
                cf: 'flipbooks/inicio',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_grupos',
                text: 'Grupos',
                active: false,
                style: '',
                icon: 'fa fa-users',
                cf: 'instituciones/grupos',
                submenu: false,
                subelements: []
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
                        text: 'Evidencias',
                        active: false,
                        icon: 'fa fa-question',
                        cf: 'quices/explorar'
                    },
                    {
                        text: 'Archivos',
                        active: false,
                        icon: 'far fa-folder',
                        cf: 'recursos/archivos'
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
                        text: 'Estadísticas',
                        active: false,
                        icon: 'fa fa-chart-line',
                        cf: 'estadisticas/ctn_correctas_incorrectas'
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