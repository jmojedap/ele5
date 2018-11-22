<?php
    
    $att_form = array(
        'class' =>  'form1'
    );

    $att_nombre_hoja = array(
        'name' => 'nombre_hoja',
        'class' =>  'form-control',
        'required'  =>  TRUE,
        'value' =>  'profesores'
    );
    
?>

<?php $this->load->view('instituciones/grupos/submenu_grupos_v') ?>

<?= form_open_multipart("instituciones/asignar_profesores_e/{$row->id}", $att_form) ?>



<div class="row">
    
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <p>
                    Aguí puede asignar los profesores de la Institución a los grupos.
                </p>

                <h4>Instrucciones:</h4>
                <ul>
                    <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                    <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                    <li>Si la casilla '<span class="resaltar">ID grupo</span>' (columna A) se encuentra vacía el profesor no será asignado.</li>
                    <li>El nombre de la hoja de cálculo dentro del archivo, de la cual se tomarán los datos, no puede contener caracteres con tildes ni letras ñ.</li>
                    <li>Desasignar formato: <?= anchor(base_url() . 'assets/formatos_cargue/14_formato_asignacion_profesores.xlsx', '14_formato_asignacion_profesores.xlsx', 'class="" title="Desasignar formato"') ?> </li>
                </ul>
            </div>
        </div>
    </div>
    
    
    <div class="col col-md-8">
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="sep1">
                    <label for="file" class="label1">Seleccionar archivo</label><br/>
                    <input type="file" name="file" required>
                </div>

                <div class="sep1">
                    <label for="nombre_hoja" class="label1">Nombre Hoja</label><br/>
                    <span class="descripcion">Digite el nombre de la hoja de cálculo en archivo de excel de donde se tomarán los datos de los profesores</span>

                    <?= form_input($att_nombre_hoja) ?>
                </div>

                <div class="sep1">
                    <input type="submit" class="btn btn-primary" value="Cargar">
                </div>
            </div>
        </div>
        
            

        
    </div>
</div>

<?= form_close() ?>




