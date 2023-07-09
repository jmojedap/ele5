<script>
    
//VARIABLES
//---------------------------------------------------------------------------------------------------
    
    //General
        var base_url = '<?= base_url() ?>';
        var tema_id = <?= $tema_id ?>;
    
    //Valores iniciales a las variables
        var carpeta_img = '<?= $carpeta_uploads . 'pf_zoom/' ?>';
        var carpeta_img_mini = '<?= $carpeta_uploads . 'pf_mini/' ?>';
        var src_img_cargue = '<?= URL_IMG ?>flipbook/pagina_cargando.png';
        var max_num_pag = <?= $paginas->num_rows() - 1 ?>;
        var num_pagina_actual = <?= $num_pagina ?>;    
        var bookmark_actual = <?= $bookmark ?>;
        var max_slider = <?= $paginas->num_rows() - 1 ?>;
            
    //Páginas
        paginas = JSON.parse('<?= json_encode($paginas->result()) ?>');
        
    //Anotaciones
        var anotaciones = new Array();
        <?php foreach ($anotaciones->result() as $row_anotacion) : ?>
            anotaciones[<?= $row_anotacion->num_pagina ?>] = '<?= $row_anotacion->anotacion ?>';
        <?php endforeach ?>
         
//DOCUMENT READY
//---------------------------------------------------------------------------------------------------

    $(document).ready(function()
    {
        
        $('[data-submenu]').submenupicker();    //Assets, bootstrap submenú
        
        //Procesos iniciaeles
            $('#indice_flipbook').hide();
            $('#guardada').hide();
        
        //Mostrar elementos de página actual
            //$('.pagina_' + num_pagina_actual).removeClass('hidden');
            $('.pagina_').removeClass('hidden');
            $('#anotacion').val(anotaciones[num_pagina_actual]);
        
        //Slider superior para cambiar de página
        $( "#slider" ).slider({
            range: "min",
            value: num_pagina_actual,
            min: 0,
            max: max_slider,
            change: function( event, ui ) {
                num_pagina_actual = ui.value;
                $('#num_pagina_actual').html(ui.value + 1);
                actualizar_pag(num_pagina_actual);
            },
            slide: function( event, ui){
                $('#num_pagina_actual').html(ui.value + 1);
            }
        });
        
        //Establecer página actual como bookmark
        $('#bookmark').click(function(){
            bookmark_actual = num_pagina_actual;
            guardar_bookmark();
            actualizar_bookmark();
        });
        
        //Botón para ir a la página siguiente
        $('#pagina_sig').click(function(){
            num_pagina_actual = parseInt(num_pagina_actual) + 1;
            num_pagina_actual = Pcrn.ciclo_entre(num_pagina_actual, 0, max_num_pag);
            $("#slider").slider('value', num_pagina_actual);
        });

        //Botón para ir a la página anterior
        $('#pagina_ant').click(function(){
            num_pagina_actual = parseInt(num_pagina_actual) - 1;
            num_pagina_actual = Pcrn.ciclo_entre(num_pagina_actual, 0, max_num_pag); 
            $("#slider").slider('value', num_pagina_actual);
        });
        
        //Botón para mostrar el índice del flipbook
        $('#mostrar_indice').click(function(){
            alternar_indice();
        });
        
        $('.link_indice').click(function(){
            num_pagina_actual = $(this).attr('id').substring(7);  //Quitar caracteres de "indice_"
            $("#slider").slider('value', num_pagina_actual);
            //alternar_indice();
        });
        
        //Botón para guardar anotación
        $('#guardar_anotacion').click(function(){
            guardar_anotacion();
        });
        
        $('.link_indice').click(function(){
            num_pagina_actual = $(this).attr('id').substring(7);  //Quitar caracteres de "indice_"
            $("#slider").slider('value', num_pagina_actual);
            alternar_indice();
        });
        
        //Botón para mostrar el índice del flipbook
        $('#alternar_menu_recursos').click(function(){
            $('#alternar_menu_recursos').toggleClass('btn-default');
            $('#alternar_menu_recursos').toggleClass('btn-info');
            $('#menu_recursos').toggleClass('hidden-xs');
            $('#menu_recursos').toggleClass('hidden-sm');
        });
        
    });
    

//FUNCIONES
//---------------------------------------------------------------------------------------------------

    //Solicitar los datos de una página específica
    function actualizar_pag(num_pagina_actual)
    {
        $('#imagen_pagina').attr('src', src_img_cargue);
        setTimeout(function(){ cambiar_img(); }, 100);
        $('#anotacion').val(anotaciones[num_pagina_actual]);
        mostrar_recursos();
        actualizar_bookmark();
        reiniciar_guardada();
        
    }
    
    /**
     * Cambia la imagen de la página
     * @returns {undefined}
     */
    function cambiar_img()
    {
        $('#imagen_pagina').attr('src', carpeta_img + paginas[num_pagina_actual].archivo_imagen);
    }
    
    //Actualizar el botón bookmark
    function actualizar_bookmark()
    {
        if ( num_pagina_actual === bookmark_actual ) {
            $('#bookmark').removeClass('btn-default');
            $('#bookmark').addClass('btn-success');
        } else {
            $('#bookmark').removeClass('btn-success');
            $('#bookmark').addClass('btn-default');
        }
    }
    
    //Establecer la página actual como bookmark
    function guardar_bookmark()
    {
	$.ajax({
            type: 'POST',
            url: base_url + 'flipbooks/guardar_bookmark/' + tema_id + '/' + num_pagina_actual
        });
    }
    
    /**
     * Mostrar recursos asociados a la página actual
     * @returns {undefined}
     */
    function mostrar_recursos()
    {
        //$('.recurso').addClass('hidden');
        $('.recurso').remove('hidden');
        $('.pagina_' + num_pagina_actual).removeClass('hidden');
    }
    
    function alternar_indice()
    {
        $('#imagen_pagina').slideToggle('fast');
        $('#indice_flipbook').slideToggle('fast');
        $('#mostrar_indice').toggleClass('btn-info');
        $('#mostrar_indice').toggleClass('btn-default'); 
    }
    
    //Guardar la anotación hecha a la página
    function guardar_anotacion()
    {

        var registro_anotacion = [];
        registro_anotacion[0] = num_pagina_actual;
        registro_anotacion[1] = $('#anotacion').val();
        
        $.ajax({        
            type: 'POST',
            url: base_url + 'flipbooks/guardar_anotacion/' + tema_id,
            data: {registro : registro_anotacion},
            success : function() {
                mostrar_guardada();
            }
        });
     
        //Modificar var anotaciones
        anotaciones[num_pagina_actual] = $('#anotacion').val();

    }
    
    /**
     * Botones de guardado de anotación, después de guardarla
     * @returns {undefined}
     */
    function mostrar_guardada()
    {
        $('#guardar_anotacion').hide();
        $('#guardada').show();
    }
    
    /**
     * Botones de guardado de anotación
     * @returns {undefined}
     */
    function reiniciar_guardada()
    {
        $('#guardar_anotacion').show();
        $('#guardada').hide();
    }
         
</script>