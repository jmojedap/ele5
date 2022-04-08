<script>
    var nav_1_elements = [
            {
                text: 'Inicio',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-home',
                cf: 'flipbooks/inicio',
                submenu: false,
                subelements: [],
                sections: []
            },
            {
                text: 'Biblioteca',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-book',
                cf: 'usuarios/biblioteca',
                submenu: false,
                subelements: [],
                sections: []
            },
            {
                text: 'Programador',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-calendar',
                cf: 'eventos/calendario',
                submenu: false,
                subelements: [],
                sections: []
            },
            {
                text: 'Noticias',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-newspaper',
                cf: 'eventos/noticias',
                submenu: false,
                subelements: [],
                sections: []
            },
            {
                text: 'Grupos',
                active: false,
                style: '',
                icon: 'fa fa-users',
                cf: 'instituciones/grupos',
                submenu: false,
                subelements: [],
                sections: []
            },
            
            {
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
                        cf: 'cuestionarios/explorar',
                        sections: []
                    },
                    {
                        text: 'Preguntas',
                        active: false,
                        icon: 'fa fa-question',
                        cf: 'preguntas/explorar',
                        sections: []
                    }
                ],
                sections: []
            },
        ];
</script>