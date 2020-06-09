<script>
    var nav_1_elements = [
            {
                id: 'nav_1_instituciones',
                text: 'Instituciones',
                active: false,
                style: '',
                icon: 'fa fa-university',
                cf: 'instituciones/explorar',
                submenu: false,
                subelements: []
            },
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
                    }
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
            }
        ];
</script>