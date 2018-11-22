<?php

    $nombre_cuestionario = array(
        'name'  =>  'nombre_cuestionario',
        'id'    =>  'nombre_cuestionario',
        'value' =>  set_value('nombre_cuestionario'),
        'class' =>  'i-texto1'
    );
    
    //Opciones nivel cuestionario
    $opciones = $this->App_model->opciones_item("categoria_id = 3", TRUE);
    
    $submit = array(
        'value' =>  'Buscar'
    );

?>

<article class="module width_3_quarter">
    <header><h3>Búsqueda</h3></header>
    <?= form_open("usuarios/buscar_cuestionarios/{$usuario_id}") ?>
        <div class="module_content">
                <p>Busque el cuestionario que desea asignar a <?= $row->nombre ?></p>

                <fieldset>
                        <div class="div1">
                            <label for="nombre_cuestionario" class="label1">Nombre cuestionario</label>
                            <?= form_input($nombre_cuestionario) ?>
                        </div>
                        <div class="div1">

                        </div>
                </fieldset>


        </div>
        <footer>
            <div class="submit_link">
                <?= form_submit($submit) ?>    
            </div>
        </footer>
    <?= form_close() ?>
    
    
</article>


    

    
    <?php if ( !is_null($cuestionarios) ):?>
        <article class="module width_3_quarter">
            <header>
                <h3>Resultados encontrados: <?= $cuestionarios->num_rows() ?></h3>
            </header>

            <!--Listado de cuestionarios encontradas-->
            <?php foreach ($cuestionarios->result() as $row_cuestionario) { ?>

                <div class="module_content">
                    <h3><?= $row_cuestionario->nombre_cuestionario ?></h3>
                    <p><?= $row_cuestionario->notas ?></p>
                    <p><?= anchor("usuarios/agregar_cuestionario/{$row->id}/{$row_cuestionario->id}", 'Agregar') ?></p>
                </div>

            <?php }?>
        </article>
    <?php endif ?>
    


    