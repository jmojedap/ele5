<script>
// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function ()
    {
        //act_sidebar();
        
        /*$('.main-sidebar a').click(function () {
            console.log($(this).data('cf'));
            app_cf = $(this).data('cf');
            act_vista_a();
        });*/
    });

// Funciones
//-----------------------------------------------------------------------------
    //Actualizar #vista_a
    function act_vista_a()
    {
        $.ajax({        
            type: 'POST',
            url: app_url + app_cf + '/?json=1',
            success: function (resultado) {
                $('#titulo_pagina').html(resultado.titulo_pagina);
                $('#vista_a').html(resultado.vista_a);
                $('#menu_a').html(resultado.menu_a);
                history.pushState(null, null, app_url + app_cf);
                act_sidebar();
            }
        });
    }
    
    function act_sidebar()
    {
        /*$('#sidebar-menu a').removeClass('subdrop');
        $('#sidebar-menu li').removeClass('subdrop');*/
        console.log(app_cf);
    }
</script>