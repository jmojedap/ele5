<?php
    
    $att_form = array(
        'class' =>  'form1'
    );

    $att_nombre_hoja = array(
        'name' => 'nombre_hoja',
        'class' =>  'form-control',
        'required'  =>  TRUE,
        'value' =>  'grupos',
        'title' =>  'Escriba el nombre de la hoja de cálculo donde están los datos de los grupos',
        'placeholder' =>  'Hoja de cálculo'
    );
    
?>

<?php $this->load->view('instituciones/grupos/submenu_grupos_v') ?>

<?= form_open_multipart("instituciones/cargar_grupos_e/{$row->id}", $att_form) ?>

<div class="row">
    <div class="col col-sm-7">
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="sep1">
                    <label for="file" class="label1">Seleccionar archivo</label><br/>
                    <input type="file" name="file" required accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>

                <div class="sep1">
                    <label for="nombre_hoja" class="label1">Nombre Hoja</label><br/>
                    <span class="descripcion">Digite el nombre de la hoja de cálculo en archivo de excel de donde se tomarán los datos de los programas</span>

                    <?= form_input($att_nombre_hoja) ?>
                </div>
                    <?= form_hidden('anio_generacion', date('Y'));  ?>

                <div class="sep1">
                    <input type="submit" class="btn btn-primary" value="Cargar">
                </div>  
            </div>
        </div>
        
        
    </div>
    
    <div class="col col-sm-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cargar grupos
            </div>
            <div class="panel-body">
                <p>
                    Aguí puede cargar los grupos de estudiantes de la Institución.
                </p>

                <h4>Instrucciones:</h4>
                <ul>
                    <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                    <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                    <li>Si la casilla '<span class="resaltar">nivel</span>' (columna A) se encuentra vacía el grupo no será creado.</li>
                    <li>El nombre de la hoja de cálculo dentro del archivo, de la cual se tomarán los datos, no puede contener caracteres con tildes ni letras ñ.</li>
                    <li>Descargar formato: <?= anchor(base_url() . 'assets/formatos_cargue/12_formato_cargue_grupos.xlsx', '12_formato_cargue_grupos.xlsx', 'class="" title="Descargar formato"') ?> </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= form_close() ?>






