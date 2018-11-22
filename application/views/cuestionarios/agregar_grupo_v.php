<?php

    $fecha_inicio = array(
        'name'  =>  'fecha_inicio',
        'id'    =>  'fecha_inicio',
        'value' =>  set_value('fecha_inicio'),
        'class' =>  'i-texto1'
    );
    
    $fecha_fin = array(
        'name'  =>  'fecha_fin',
        'id'    =>  'fecha_fin',
        'value' =>  set_value('fecha_fin'),
        'class' =>  'i-texto1'
    );
    
    //Opciones nivel grupo
    $opciones = $this->App_model->opciones_item("categoria_id = 3", TRUE);
    //$dropdown = form_dropdown('nivel', $opciones, set_value('nivel'),'class="i-texto1"');
    
    $submit = array(
        'value' =>  'Guardar'
    )

?>

    

<article class="module width_3_quarter">
    <header>
        <h3>Parámetros</h3>
    </header>
    
    <?php if ( $num_insertados > 0 ):?>
        <h4 class="alert_success">Fueron agregados <?= $num_insertados ?> estudiantes</h4>
    <?php endif ?>
    
    <?= form_open("cuestionarios/agregar_grupo/{$cuestionario_id}/{$grupo_id}") ?>
        <?= form_hidden('cuestionario_id', $cuestionario_id) ?>
        <?= form_hidden('grupo_id', $grupo_id) ?>
    
        <div class="module_content">
            
            <p>Defina los parámetros para la asignación del cuestionario</p>
            
            <div class="gris-1">
                <div class="div1">
                    <label for="fecha_inicio" class="label1">Fecha apertura</label>
                    <?= form_input($fecha_inicio) ?>
                </div>
                <div class="div1">
                    <label for="fecha_cierre" class="label1">Fecha de cierre:</label>
                    <?= form_input($fecha_fin) ?>
                </div>
                
            </div>
            
        </div>

        <footer>
            <div class="submit_link">
                <?= form_submit($submit) ?>    
            </div>
        </footer>
    <?= form_close() ?>
    
</article>

    