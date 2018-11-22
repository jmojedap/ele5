<?php 
    $att_num_pagina = array(
        'id' => 'campo-num_pagina',
        'class' => 'form-control',
        //'style' => 'width: 70px',
        'type' => 'number',
        'value' => $num_pagina + 1,
        'min' => 1,
        'max' => $max_pagina + 1,
        'title' => $max_pagina + 1 . ' páginas en total'
    );
?>

<div class="pull-right" style="max-width: 125px">
    <div class="input-group">
        <span class="input-group-btn">
            <button id="btn_explorar_ant" class="btn btn-default" type="button" title="Página anterior">
                <i class="fa fa-caret-left"></i>
            </button>
        </span>
        <?php echo form_input($att_num_pagina) ?>
        <span class="input-group-btn">
            <button id="btn_explorar_sig" class="btn btn-default" type="button" title="Página siguiente">
                <i class="fa fa-caret-right"></i>
            </button>
        </span>
    </div>
</div>

