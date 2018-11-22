<script>
// Document Ready
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var cuestionario_id = '<?php echo $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('.bs_datepicker').datepicker()
            .on('changeDate', function(e) {
            validar_fechas();
        });
        
        $('#campo-institucion_id').change(function(){
            var institucion_id = $(this).val();
            console.log(institucion_id);
            window.location = base_url + 'cuestionarios/n_asignar/' + cuestionario_id + '/?i=' + institucion_id;
        });
        
        $('#campo-nivel').change(function(){
            var institucion_id = '<?php echo $busqueda['i'] ?>';
            var nivel = $(this).val();
            console.log(institucion_id);
            window.location = base_url + 'cuestionarios/n_asignar/' + cuestionario_id + '/?i=' + institucion_id + '&n=' + nivel;
        });
        
        $('#app_asignar').on('click', '.btn_reiniciar_uc', function(){
            console.log('REINICIANDO');
        });
        
        $('#app_asignar').on('change', '#check_todos', function(){
            if ( $(this).is(':checked') ) {
                console.log('todos');
                $('.check_registro').prop('checked', true);
            } else {
                console.log('ninguno');
                $('.check_registro').prop('checked', false);
            }
        });
        
        $('#app_asignar').on('change', '.check_registro', function(){
            //console.log($(this).data('usuario_id'));
            $('.check_registro').each(function(){
                if ( $(this).prop('checked') ) {
                    console.log($(this).data('usuario_id'));
                }
            });
        });
    });
    
    //Verificar fecha fin posterior a fecha inicio
    function validar_fechas()
    {
        if ( $('#campo-fecha_fin').val() < $('#campo-fecha_inicio').val() )
        {
            $('#campo-fecha_fin').datepicker('update', $('#campo-fecha_inicio').val());
            console.log('Ajustando fechas');
        }
    }
</script>