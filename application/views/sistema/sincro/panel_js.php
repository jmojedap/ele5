<script src="<?= base_url('js/Math.uuid.js') ?>"></script>

<script>
//Variables 
//-----------------------------------------------------------------------------
        var base_url = '<?= base_url() ?>';
        var sincro_url = '<?= $sincro_url ?>';
        
        var json_descarga = '';
        var json_estado_tablas = '';
        
        var tabla = '';             //Tabla actual
        var metodo_id_url = <?= $metodo_id ?>;      //Método de sincronización en URL
        var metodo_id = <?= $metodo_id ?>;          //Método de sincronización actual
        var desde_id = 0;           //ID desde el cual se descargan los registros
        var limit = <?= $limit ?>;  //Número máximo de registros por ciclo de descarga
        var cant_registros = 0;     //Cantidad de registros en la tabla actual
        var cant_ciclos = 1;        //Cantidad de ciclos necesarios para descargar todos los datos
        var cant_sincronizados = 0; //Cantidad de registros sincronizados actualmente
        var ciclo = 0;              //Número del ciclo actual
        var offset = 0;             //Número del registro actual en sincronización
        var porcentaje = 0;         //Porcentaje de registros sincronizados
        
        var mensaje = '';

//Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        
        /**
         * Al hacer clic en el botón de sincronización
         * @returns {undefined}
         */
        $('.sincro').click(function(){
            
            tabla = $(this).data('table');          //Identificar la tabla
            desde_id = $(this).data('desde_id');    //Max ID de la tabla local
            
            metodo_id = $(this).data('metodo_id');  //Método automático definido para la tabla
            if ( metodo_id_url > 0 )
            {
                metodo_id = metodo_id_url;          //Método definido en la URL
            }
            
            iniciar_sincro();                       //Marcar inicio de sincronización en la tabla sis_tabla
            if ( metodo_id == 1 ) 
            {
                desde_id = 0;       //Para importar todos los registros
                limpiar_tabla();
            }      //Eliminar todos los registros de la tabla local
            calcular_ciclos();                      //Inicia todo el proceso de sincronización
        });
        
        $('#act_estado_servidor').click(function(){
            act_estado_servidor();
        });
    });


//Funciones
//-----------------------------------------------------------------------------
    
    
    
    /**
     * Actualiza el estado de las tablas del servidor, en la tabla local sis_tabla
     * 
     * @returns {undefined}
     */
    function act_estado_servidor()
    {
        $.ajax({        
            type: 'POST',
            url: sincro_url + 'json_estado_tablas/' + tabla,
            beforeSend : function(){
                $('#act_datos_servidor').html('Calculando');
            },
            success: function(respuesta){
                json_estado_tablas = respuesta;
                cargar_estado_servidor();
                //console.log(respuesta);
            }
        });
    }
    
    /**
    * Si es exitosa la función act_estado_servidor()
    * Se usan los datos JSON descargados para cargarlos
    * en la tabla local sis_tabla, campos: max_ids, cant_registros_servidor
    */
    function cargar_estado_servidor()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'sincro/act_estado_servidor/',
            data: {
                json_estado_tablas : json_estado_tablas
            },
            beforeSend: function(){
                $('#act_estado_servidor').html('<i class="fa fa-database"></i> Guardando');
            },
            success: function(respuesta){
                console.log(respuesta);
                window.location = base_url + 'sincro/panel/';
                
            }
        });
    }
    
    /**
     * AJAX
     * En local, actualiza el campo sis_tabla.fecha_inicio
     */
    function iniciar_sincro(){
        $.ajax({       
            type: 'POST',
            url: base_url + 'sincro/iniciar_sincro/' + tabla
        });
    }
    
    /**
     * AJAX
     * En local, elimina todos los registros de la tabla
     * @returns {undefined}
     */
    function limpiar_tabla(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'sincro/limpiar_tabla/' + tabla,
            beforeSend : function(){
                $('#estado_' + tabla).html('Limpiando tabla local...');
            },
            success: function(){
                $('#barra_porcentaje_' + tabla).css("width", '0%'); 
            }
        });
    }
    
    /**
     * Conociendo el número de registros de una tabla se calcula el número de ciclos
     * de descarga de registros para sincronización, según el límite de registros por ciclo
     * 
     * @returns {undefined}
     */
    function calcular_ciclos()
    {
        $.ajax({        
            type: 'POST',
            url: sincro_url + 'cant_registros/' + tabla + '/' + desde_id,
            beforeSend : function()
            {
                $('#estado_' + tabla).html('Calculando...');
            },
            success: function(respuesta)
            {   
                cant_registros = respuesta;
                cant_ciclos = Math.ceil( cant_registros / limit);
                boton_proceso();                                    //Pone el botón en formato estado "En proceso"
                siguiente_ciclo();
            }
        });
    }
    
    
    
    /**
     * Siguiente ciclo en la sincronización
     * La sincronización se hace por ciclos (paquetes de registros) por la 
     * imposibidad de descargar todos los registros en JSON en una sola transacción
     * 
     * @type Arguments
     */
    function siguiente_ciclo()
    {
        if ( ciclo < cant_ciclos  ) //Faltan ciclos
        {
            offset = ciclo * limit; //Número del registro
            sincronizar();
        } else {
            //Ciclos terminados
            reiniciar_variables();  //Para siguiente tabla
            boton_reiniciar();      //Reestablecer botón
            act_datos_sincro();     
            mostrar_resultado();
        }
    }
    
    //Descargar los datos del Servidor y guardarlos en la BD local
    function sincronizar()
    {
        $.ajax({
            type: 'POST',
            //url:  sincro_url + 'registros_json/' +  tabla + '/' + limit + '/' + offset,
            url:  sincro_url + 'registros_json_id/' +  tabla + '/' + limit + '/' + desde_id,
            beforeSend : function(){
                var ciclo_mostrar = ciclo + 1;
                $('#estado_' + tabla).html('<i class="fa fa-download"></i> ' + ciclo_mostrar + '/' + cant_ciclos);
            },
            success: function(respuesta){
                json_descarga = respuesta;
                cargar_datos();
            }
        });
    }
    
    /**
    * Si es exitosa la función sincronizar()
    * Se usan los datos JSON descargados para cargarlos
    * en la base de datos local
    */
    function cargar_datos()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'sincro/cargar_datos/' + tabla,
            data: {
                json_descarga : json_descarga
            },
            beforeSend: function(){
                var ciclo_mostrar = ciclo + 1;
                $('#estado_' + tabla).html('<i class="fa fa-floppy-o"></i> ' + ciclo_mostrar + '/' + cant_ciclos);
            },
            success: function(respuesta){
                ciclo++;    //Aumenta para siguiente ciclo
                desde_id = respuesta.max_id;
                cant_sincronizados += respuesta.cant_registros;
                //alert(cant_sincronizados);
                resultado_parcial();
                siguiente_ciclo();
            }
        });
    }
    
    /**
    * Actualiza los datos de sincronización en la tabla sis_tabla
    * 
    */
    function act_datos_sincro()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'sincro/act_datos_sincro/' + tabla,
            data: {
                cant_registros : cant_registros
            },
            success : function(){
                mostrar_resultado();
            }
        });
    }
    
    /**
    * Si cargar_datos() es exitosa
    * Se muestra en la vista el resultado parcial del proceso de sincronización
    * 
    */
    function resultado_parcial()
    {
        porcentaje = Math.ceil( 100 * ( cant_sincronizados / cant_registros ) );
        $('#cant_sincronizados_' + tabla).html(cant_sincronizados + '<br/>' + cant_registros); 
        //$('#porcentaje_' + tabla).html(porcentaje + '%'); 
        $('#barra_porcentaje_' + tabla).css("width",  porcentaje + '%'); 
    }
    
    function mostrar_resultado()
    {
        $('#estado_' + tabla).html('<i class="fa fa-check"></i> Finalizado');
        $('#tiempo_hace_' + tabla).html('1 min');
        $('#tiempo_hace_' + tabla).removeClass('info');
        $('#tiempo_hace_' + tabla).addClass('success');
        $('#cant_registros_' + tabla).html(cant_registros);
    }
    
    function reiniciar_variables()
    {
        cant_sincronizados = 0;
        cant_ciclos = 0;
        ciclo = 0;
        offset = 0;
        porcentaje = 0;
    }
    
    /**
     * Actualiza el botón, estado en proceso
     */
    function boton_proceso()
    {
        $('#sincro_' + tabla).html('<i class="fa fa-refresh fa-spin"></i>');
        $('#sincro_' + tabla).removeClass('btn-warning');
        $('#sincro_' + tabla).addClass('btn-info');
    }
    
    /**
     * Reestablecer formato botón, al finalizar sincronización
     */
    function boton_reiniciar()
    {
        $('#sincro_' + tabla).html('<i class="fa fa-refresh"></i>');
        $('#sincro_' + tabla).removeClass('btn-info');
        $('#sincro_' + tabla).addClass('btn-warning');
    }
</script>