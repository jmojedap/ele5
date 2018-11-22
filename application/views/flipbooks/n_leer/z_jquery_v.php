<script>
// Variables
//-----------------------------------------------------------------------------    
    var num_pagina = '<?php echo $num_pagina ?>';
    var max_num_pag = <?php echo $row->num_paginas - 1 ?>;
    
// Document Ready
//-----------------------------------------------------------------------------
    
    $(document).ready(function ()
    {
        console.log('EMPEZANDO');
        
        //Slider superior para cambiar de página
        $( "#slider" ).slider({
            range: "min",
            value: <?php echo $bookmark ?>,
            min: 0,
            max: max_num_pag,
            change: function( event, ui ) {
                num_pagina = ui.value;
                console.log('num_pagina: ' + num_pagina);
            },
            slide: function( event, ui){
                /*$('#num_pagina_actual').html(ui.value + 1);*/
            }
        });
    });
</script>