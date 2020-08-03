<script>
    var nav_1_elements = [
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
                text: 'Mis desempeños',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-check',
                cf: 'usuarios/quices/0',
                submenu: false,
                subelements: [],
                sections: []
            },
            {
                text: 'Tienda',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-shopping-cart',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Catálogo',
                        active: false,
                        icon: 'fa fa-book',
                        cf: 'products/catalog',
                        sections: []
                    },
                    {
                        text: 'Mis compras',
                        active: false,
                        icon: 'fa fa-shopping-cart',
                        cf: 'orders/my_orders',
                        sections: ['orders/my_orders', 'orders/status']
                    },
                ],
                sections: ['orders/checkout']
            }
        ];
</script>