<?php
    
    //Resultados de cargue, si es que aplica
        $res_proceso = $this->session->flashdata('res_proceso');

    $att_form = array(
        'class' =>  'form1'
    );

    $att_nombre_hoja = array(
        'name' => 'nombre_hoja',
        'class' =>  'i-texto1',
        'required'  =>  'required',
        'value' =>  'preguntas'
    );
    

?>

<?php $this->load->view('preguntas/explorar_menu_v') ?>

<?= form_open_multipart("preguntas/cargar_masivo_e", $att_form) ?>

<?php if ( strlen($res_proceso['mensaje']) > 0 ){ ?>
    <div class="alert alert-info" role="alert">
        <?= $res_proceso['mensaje'] ?>
    </div>    
<?php } ?>

<div class="seccion group">
    
    <div class="col col_box span_1_of_3">
        <div class="info_container_body">

            <p>
                Aguí puede cargar masivamente las preguntas a los temas.
            </p>
            
            <h4>Instrucciones:</h4>
            <ul>
                <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                <li>Si la casilla '<span class="resaltar">texto pregunta</span>' (columna B) se encuentra vacía la pregunta no será cargada.</li>
                <li>El nombre de la hoja de cálculo dentro del archivo, de la cual se tomarán los datos, no puede contener caracteres con tildes ni letras ñ.</li>
                <li>Descargar formato: <?= anchor(base_url() . 'assets/formatos_cargue/16_formato_cargue_preguntas.xlsx', '16_formato_cargue_preguntas.xlsx', 'class="" title="Descargar formato"') ?> </li>
            </ul>
        </div>
    </div>
    
    
    <div class="col span_2_of_3">
        
        <div class="gris-1">
            <div class="div1">
                <label for="file" class="label1">Seleccionar archivo</label><br/>
                <input type="file" name="file" required>
            </div>

            <div class="div1">
                <label for="nombre_hoja" class="label1">Nombre Hoja</label><br/>
                <span class="descripcion">Digite el nombre de la hoja de cálculo en archivo de excel de donde se tomarán los datos de los programas</span>

                <?= form_input($att_nombre_hoja) ?>
            </div>

            <div class="div1">
                <input type="submit" class="button orange" value="Cargar">
            </div>

        </div>
        
        <div class="info_container_body">
            
            <?php //Mensajes de validación del formulario ?>

            <?php if ( ! $cargado ):?>
                <div class="div1">
                    <?php foreach ($resultado as $mensaje_resultado): ?>
                        <h4 class="alert_error"><?= $mensaje ?></h4>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <?php if ( validation_errors() ):?>
                <div class="div1">
                    <h4 class="alert_error"><?= validation_errors() ?></h4>    
                </div>
            <?php endif ?>
            
        </div>
    </div>
</div>

<?= form_close() ?>




