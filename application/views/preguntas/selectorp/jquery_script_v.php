<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var str_preguntas = '<?php echo $str_preguntas ?>';

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#selector_form').submit(function(){
            //console.log('enviando');
            create_cuestionario();
            return false;
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function create_cuestionario(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'cuestionarios/selectorp_create',
            data: $('#selector_form').serialize(),
            success: function(response){
                console.log(response.cuestionario_id);
                if ( response.cuestionario_id > 0 ) {
                    window.location = base_url + 'cuestionarios/asignar/' + response.cuestionario_id
                }
            }
        });
    }

</script>