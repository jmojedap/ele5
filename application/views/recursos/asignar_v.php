<?php
    $att_form = array(
        'class' =>  'form1'
    );
?>

<?= $this->load->view('recursos/menu_archivos_v') ?>

<?= form_open_multipart("recursos/procesar_asignar", $att_form) ?>

<div class="seccion group">
    
    <div class="col col_box span_1_of_3">
        <div class="info_container_body">
            <h4>Instrucciones para cargar lista de asignación de archivos a temas</h4>
            <ul>
                <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                <li>Si alguna casilla del registro está vacía el archivo no será asignado</li>
                <li>El nombre de la hoja de cálculo dentro del archivo, de la cual se tomarán los datos, no puede contener caracteres con tildes ni letras ñ.</li>
            </ul>
        </div>
    </div>
    
    
    <div class="col col_box span_2_of_3">
        <div class="info_container_body">      
            <div class="div1">
                <label for="file" class="label1">Seleccionar archivo</label><br/>
                <input type="file" name="file" required>
            </div>

            <div class="div1">
                <label for="nombre_hoja" class="label1">Nombre Hoja</label><br/>
                <span class="descripcion">Digite el nombre de la hoja de cálculo dentro del archivo de excel de donde se tomarán los datos de los archivos</span>

                <input type="text" name="nombre_hoja" class="i-texto1" required>
            </div>
            
            <div class="div1">
                <input type="submit" class="button orange" value="Importar">
            </div>
            
            
            <?php //Mensajes de validación del formulario ?>

            <?php if ( isset($resultado) ):?>
                <div class="div1">
                    <?php foreach ($resultado as $mensaje_resultado): ?>
                        <h4 class="alert_error"><?= $mensaje_resultado ?></h4>
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




