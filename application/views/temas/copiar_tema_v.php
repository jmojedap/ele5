<?php
    $att_nombre_tema = array(
        'name' => 'nombre_tema',
        'class' => 'form-control',
        'value' => "{$row->nombre_tema} - Copia",
        'required' => 'required'
    );
        
    $att_cod_tema = array(
        'name' => 'cod_tema',
        'class' => 'form-control',
        'value' => $row->cod_tema,
        'required' => 'required'
    );
        
    $att_descripcion = array(
        'name' => 'descripcion',
        'class' => 'form-control',
        'value' => "Copia de {$row->nombre_tema} | {$row->descripcion}",
        'rows' =>   3
    );
?>

<div style="max-width: 750px; margin: 0 auto;">
    <div class="card card-default mb-2">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="form_id" action="<?php echo base_url($destino_form) ?>">

                <?= form_hidden('tema_id', $row->id) ?>
                <div class="form-group row">
                    <label for="cod_tema" class="col-md-3 control-label">Código tema</label>
                    <div class="col-md-9">
                        <?= form_input($att_cod_tema) ?>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="nombre_tema" class="col-md-3 control-label">Nombre del tema</label>
                    <div class="col-md-9">
                        <?= form_input($att_nombre_tema) ?>
                    </div>
                </div>
            
                <div class="form-group row">
                    <label for="descripcion" class="col-md-3 control-label">Descripción</label>
                    <div class="col-md-9">
                        <?= form_textarea($att_descripcion) ?>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="offset-md-3 col-md-9">
                        <button class="btn btn-primary btn-block" type="submit">
                            Crear
                        </button>
                    </div>
                </div>
            </form>

        </div>
        
    </div>

    <?php if ( validation_errors() ):?>
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
    <?php endif ?>
</div>

