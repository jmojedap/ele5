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
            window.location = base_url + 'cuestionarios/n_asignar/' + cuestionario_id + '/' + institucion_id;
        });
        
        $('#app_asignar').on('click', '.btn_reiniciar_uc', function(){
            
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
    
    //Ajax
    function reiniciar_uc()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'cuestionarios/reiniciar' + '/',
            data: {
                elemento_id: elemento_id
            },
            success: function (rta) {
                //console.log(rta);
            }
        });
    }
</script>