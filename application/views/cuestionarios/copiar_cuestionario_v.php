<?php   
    $att_descripcion = array(
        'name' => 'descripcion',
        'class' => 'form-control',
        'rows' => 5,
        'value' => "Copia de {$row->nombre_cuestionario} | {$row->descripcion}"
    );
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <?php echo form_open('cuestionarios/generar_copia') ?>
            <?php echo form_hidden('cuestionario_id', $row->id) ?>
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre_cuestionario" class="label1">Nombre del cuestionario</label><br/>
                        <input
                            type="text"
                            name="nombre_cuestionario"
                            class="form-control"
                            placeholder="nombre cuestionario"
                            title="nombre cuestionario"
                            value=""<?php echo "{$row->nombre_cuestionario} - Copia" ?>
                            >
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="label1">Descripción</label><br/>
                        <?php echo form_textarea($att_descripcion) ?>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-lg btn-block" type="submit">Crear</button>
                    </div>
                </div>
            </div>
                

        <?php echo form_close() ?>

        <?php if ( validation_errors() ):?>
            <div class="modulo2 width_full">
                <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
            </div>
        <?php endif ?>
    </div>
</div>