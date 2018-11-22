<?php
    //Controles formulario
        $att_nombre_hoja = array(
            'name'   => 'nombre_hoja',
            'class'  => 'form-control',
            'required'  => 'required',
            'value'   => 'cuestionarios',
            'title' => 'Escriba el nombre de la hoja de cálculo'
        );

?>

<?php $this->load->view($vista_menu) ?>

<?= form_open_multipart($destino_form) ?>

<div class="div2">
    <div class="row">

        <div class="col col-md-7">
            <div class="panel panel-default">
                <div class="panel-body">      
                    <div class="div1">
                        <label for="file" class="">Seleccionar archivo</label><br/>
                        <input type="file" class="form-control" name="file" required>
                    </div>

                    <div class="div1">
                        <label for="nombre_hoja" class="label1">Nombre Hoja</label><br/>
                        <span class="descripcion">Digite el nombre de la hoja de cálculo dentro del archivo de excel de donde se tomarán los datos de los cuestionarios</span>

                        <?= form_input($att_nombre_hoja) ?>
                    </div>

                    <div class="div1">
                        <input type="submit" class="btn btn-primary" value="Importar">
                    </div>


                    <?php //Mensajes de validación del formulario ?>

                    <?php if ( isset($resultado) ):?>
                        <div class="div1">
                            <?php foreach ($resultado as $mensaje_resultado): ?>
                                <div class="alert alert-danger">
                                    <?= $mensaje_resultado ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                </div>
            </div>

        </div>

        <div class="col col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ¿Cómo asignar cuestionarios?
                </div>
                <div class="panel-body">
                    <p>
                        Se asignarán cuestionarios al grupo <span class="resaltar"><?= $this->App_model->nombre_grupo($row->id, 1); ?></span>
                        correspondiente a la institución <span class="resaltar"><?= $this->App_model->nombre_institucion($row->institucion_id); ?></span>.
                        Actualmente este grupo cuenta con <span class="resaltar"><?= $row->num_cuestionarios ?></span> cuestionarios registrados.
                    </p>

                    <h4>Instrucciones para asignar cuestionarios desde archivos</h4>
                    <ul>
                        <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                        <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                        <li>Si el cuestionario relacionado en la casilla '<span class="resaltar">ID cuestionario</span>' (columna A) no existe el grupo no será asignado.</li>
                        <li>Si el grupo relacionado en la casilla '<span class="resaltar">ID grupo</span>' (columna B) no existe el grupo no será asignado.</li>
                        <li>El nombre de la hoja de cálculo dentro del archivo, de la cual se tomarán los datos, no puede contener caracteres con tildes ni letras ñ.</li>
                        <li>Descargar formato: <?= anchor(URL_ASSETS . 'formatos_cargue/17_formato_asignacion_cuestionarios.xlsx', '17_formato_asignacion_cuestionarios.xlsx', 'class="" title="Desasignar formato"') ?> </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>   
</div>

<?= form_close() ?>





