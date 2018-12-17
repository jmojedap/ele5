<?php 
    $att_num_pagina = array(
        'id' => 'campo-num_pagina',
        'class' => 'form-control',
        'type' => 'number',
        'value' => $num_pagina,
        'min' => 1,
        'max' => $max_pagina,
        'title' => $max_pagina . ' páginas en total'
    );
?>

<div class="float-right" style="max-width: 125px">
    <div class="input-group">
        <span class="input-group-prepend">
            <button id="btn_explorar_ant" class="btn btn-default" type="button" title="Página anterior">
                <i class="fa fa-caret-left"></i>
            </button>
        </span>
        <?php echo form_input($att_num_pagina) ?>
        <span class="input-group-append">
            <button id="btn_explorar_sig" class="btn btn-default" type="button" title="Página siguiente">
                <i class="fa fa-caret-right"></i>
            </button>
        </span>
    </div>
</div>