<script>
// Variables
//-----------------------------------------------------------------------------

    var base_url = '<?php echo base_url() ?>';
    var id_alfanumerico = Math.uuid(16, 16);
    var quiz_id = <?= $row->id ?>;
    var tipo_id = 1;    //Tipo de elemento, campo para los quices i
    var orden = <?= $row->cant_elementos ?>;
    var texto = '';
    var detalle = '';
    var x = 0;
    var y = 0;
    var alto = 30;
    var ancho = 100;
    
    var cant_elementos = <?= $row->cant_elementos ?>;
    var arr_elementos = JSON.parse('<?= $arr_elementos ?>');

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function() {

        //Botón guardar_elemento
        /*$('#guardar_elemento').click(function(){
            cargar_variables();
            guardar_elemento();
        });*/
        
        //Botón eliminar
        $('.eliminar_elemento').click(function(){
            id_alfanumerico = $(this).attr('id').substring(9);  //Quitar caracteres de "eliminar_"
            eliminar_elemento(id_alfanumerico);
            $('#elemento_' + id_alfanumerico).hide('slow');
            $('#draggable_' + id_alfanumerico).hide('slow');    //Eliminar caja en la imagen
        });
        
        //Botón editar
        $('.editar_elemento').click(function(){
            orden = $(this).attr('id').substring(7);  //Quitar caracteres de "editar_"
            id_alfanumerico = arr_elementos[orden]['id_alfanumerico'];
            cargar_formulario();
            $('#guardar_elemento').html('Actualizar');
            $('#detalle').focus();
        });
        
        //Botón [Nuevo]
        $('#nuevo_elemento').click(function(){
            id_alfanumerico = Math.uuid(16, 16);
            orden = cant_elementos;
            limpiar_formulario();
            $('#guardar_elemento').html('Agregar');
            $('#detalle').focus();
        });

        /*
         * Script para habilitar la funcionalidad de
           arrastrar los divs visuales
           sobre la imagen principal del quiz
           todo se hace por medio de JQuery UI
         */
        $(".draggable").draggable({
            containment: "#quiz_container", 
            scroll: false,
            stop: function(e){
                cargar_variables_pos(e);
                guardar_elemento_pos();
            }
        });

    });

// Funciones
//-----------------------------------------------------------------------------

    //Guardar en la tabla quiz_elemento,
    //Incluye insertar y actualizar, dependiendo del id_alfanumerico
    function guardar_elemento()
    {
        $.ajax({
            type: 'POST',
            url: base_url +'quices/guardar_elemento',
            data: {
                id_alfanumerico : id_alfanumerico,
                quiz_id : quiz_id,
                tipo_id : tipo_id,
                orden : orden,
                texto : texto,
                detalle : detalle,
                clave : orden,
                x : x,
                y : y,
                alto : alto,
                ancho : ancho
            },
            success: function(){    //Actualizar la ventana
                window.location = base_url + 'quices/construir/' + quiz_id;
            }
        });
    }

    //Guardar en la tabla quiz_elemento, variables de posicionamiento y tamaño
    //Incluye insertar y actualizar, dependiendo del id_alfanumerico
    function guardar_elemento_pos()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'quices/guardar_elemento_pos',
            data: {
                id_alfanumerico : id_alfanumerico,
                quiz_id : quiz_id,
                tipo_id : 1,
                x : x,
                y : y,
                alto : alto,
                ancho : ancho
            }
        });
        reiniciar_variables();  //Para un nuevo elemento
    }

    function eliminar_elemento()
    {
        $.ajax({  
           type: 'POST',
           url: base_url + 'quices/eliminar_elemento/' + id_alfanumerico
        });
    }

    function cargar_formulario()
    {
        x = arr_elementos[orden]['x'];
        y = arr_elementos[orden]['y'];
        alto = arr_elementos[orden]['alto'];
        ancho = arr_elementos[orden]['ancho'];
    }

    function cargar_variables()
    {
        texto = $('#texto').val();
        detalle = $('#detalle').val();
    }
    
    function cargar_variables_pos(e)
    {
        id_alfanumerico = $(e.target).data('id_alfanumerico');
        x = $(e.target).position().left;
        y = $(e.target).position().top;
        ancho = $(e.target).css('width').replace('px', '');
        alto = $(e.target).css('height').replace('px', '');
    }
    
    //Alistamiento para agregar un nuevo elemento
    function reiniciar_variables()
    {
        id_alfanumerico = Math.uuid(16, 16);
        texto = '';
        detalle = '';
        x = 0;
        y = 0;
        alto = 30;
        ancho = 100;
    }
</script>