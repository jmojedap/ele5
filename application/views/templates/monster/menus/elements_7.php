<script>
    var nav_1_elements = [
            {
                text: 'Cuestionarios',
                active: false,
                style: '',
                icon: 'fa fa-question',
                cf: '',
                submenu: false,
                subelements: [],
                sections: []
            },
            {
                text: 'Comercial',
                active: false,
                style: '',
                icon: 'fa fa-shopping-cart',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Ventas',
                        active: false,
                        icon: 'fa fa-shopping-cart',
                        cf: 'orders/explore',
                        sections: []
                    },
                    {
                        text: 'Productos',
                        active: false,
                        icon: 'fa fa-book',
                        cf: 'products/explore',
                        sections: ['products/explore', 'products/import', 'products/import_e', 'products/add', 'products/info']
                    }
                ],
                sections: ['products/info']
            },
        ];
</script>