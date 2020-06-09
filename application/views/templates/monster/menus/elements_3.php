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
                id: 'nav_1_calendar',
                text: 'Programador',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-calendar',
                cf: 'eventos/calendario',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_noticias',
                text: 'Noticias',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-newspaper',
                cf: 'eventos/noticias',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_institucion',
                text: 'Institución',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-university',
                cf: 'instituciones/grupos',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_cuestionarios',
                text: 'Cuestionarios',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-question',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Cuestionarios',
                        active: false,
                        icon: 'fa fa-fw fa-book',
                        cf: 'cuestionarios/explorar'
                    },
                    {
                        text: 'Preguntas',
                        active: false,
                        icon: 'fa fa-fw fa-question',
                        cf: 'preguntas/explorar'
                    }
                ]
            },
            {
                id: 'nav_1_estadisticas',
                text: 'Estadísticas',
                active: false,
                style: '',
                icon: 'fa fa-chart-line',
                cf: 'estadisticas/login_usuarios',
                submenu: false,
                subelements: []
            },

        ];
</script>