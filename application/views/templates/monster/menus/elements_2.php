<script>
    var nav_1_elements = [
            {
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
                        cf: 'instituciones/explorar',
                        sections: ['instituciones/explorar', 'instituciones/nuevo']
                    },
                    {
                        text: 'Grupos',
                        active: false,
                        icon: 'fa fa-users',
                        cf: 'grupos/explorar',
                        sections: []
                    }
                ],
                sections: ['instituciones/explorar', 'instituciones/nuevo']
            },
            {
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
                        cf: 'programas/explorar',
                        sections: []
                    },
                    {
                        text: 'Contenidos',
                        active: false,
                        icon: 'fa fa-book',
                        cf: 'flipbooks/explorar',
                        sections: []
                    },
                    {
                        text: 'Temas',
                        active: false,
                        icon: 'fa fa-bars',
                        cf: 'temas/explorar',
                        sections: []
                    },
                    {
                        text: 'Quices',
                        active: false,
                        icon: 'fa fa-question',
                        cf: 'quices/explorar',
                        sections: []
                    },
                    {
                        text: 'Archivos',
                        active: false,
                        icon: 'far fa-folder',
                        cf: 'recursos/archivos',
                        sections: []
                    }
                ],
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
                        text: 'Lecturas',
                        active: false,
                        icon: 'fa fa-quote-left',
                        cf: 'datos/enunciados',
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
            {
                text: 'Administración',
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
                        cf: 'estadisticas/ctn_correctas_incorrectas',
                        sections: []
                    },
                    {
                        text: 'Ayuda',
                        active: false,
                        icon: 'fa fa-question-circle',
                        cf: 'datos/ayudas',
                        sections: ['datos/ayudas']
                    }
                ],
                sections: []
            },
            {
                text: 'Mensajes',
                active: false,
                style: '',
                icon: 'fa fa-comments',
                cf: 'mensajes/conversacion/0',
                submenu: false,
                subelements: [],
                sections: []
            },
        ];
</script>