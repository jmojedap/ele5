<?php $this->load->view('temas/menu_explorar_v') ?>

<?= form_open_multipart("temas/resultado_cargue/") ?>

<div class="seccion group">
    
    <div class="col col_box span_1_of_3">
        <div class="info_container_body">
            
            <h4>Instrucciones para cargar temas desde archivo Excel</h4>
            <ul>
                <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                <li>Si la casilla '<span class="resaltar">Cód. tema</span>' (columna B) se encuentra vacía el usuario no será cargado.</li>
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
                <span class="descripcion">Digite el nombre de la hoja de cálculo en archivo de excel de donde se tomarán los datos de los temas</span>

                <input type="text" name="nombre_hoja" class="i-texto1" required>
            </div>
            
            <div class="div1">
                <input type="submit" class="button orange" value="Importar">
            </div>
            
            
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




