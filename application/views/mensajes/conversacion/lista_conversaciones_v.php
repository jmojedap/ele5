

<div class="card card-default d-none">
    <div class="card-body">
        <?= anchor("mensajes/explorar", '<i class="fa fa-search"></i> Ver todos', 'class="btn btn-secondary" title=""') ?>
    </div>
</div>

<div class="list-group">
    
    <div class="list-group-item">
        <?= form_open($destino_form, $att_form) ?>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                </div>
                <input
                    name="q" type="text" class="form-control"
                    required
                    title="Busque una conversación" placeholder="Busque una conversación"
                    value="<?= $busqueda['q'] ?>"
                >
                
                <?php if ( strlen($busqueda['q']) > 0) { ?>
                    <span class="input-group-btn">
                        <?= anchor("mensajes/conversacion/{$row->id}/", '<i class="fa fa-times"></i>', 'class="btn btn-secondary" title=""') ?>
                    </span>
                <?php } ?>
            </div>    
        <?= form_close('') ?>
    </div>
    
    <?php if ( $conversaciones->num_rows() == 0 ) { ?>
        <div class="alert alert-warning" role="alert">
            No se encontraron conversaciones
        </div>
    
        <?= anchor('mensajes/nuevo', '<i class="fa fa-plus"></i> Nuevo', 'class="btn btn-info btn-block" title="Nuevo mensaje"') ?>
    <?php } ?>
    
    <?php foreach ($conversaciones->result() as $row_conversacion) : ?>        
        <?php
            $clase = 'list-group-item';
            if ( $row_conversacion->id == $row->id ) { $clase .= ' active'; }

            $asunto = 'Nuevo mensaje';
            if ( strlen($row_conversacion->asunto) > 0 ) { $asunto = substr($row_conversacion->asunto, 0, 50); }

            $no_leidos = $this->Mensaje_model->no_leidos($row_conversacion->id);
            $texto = $asunto;

            if ( $no_leidos > 0 ) { $texto .= ' <span class="etiqueta primario">' . $no_leidos . '</span>'; }           

            $href = base_url("mensajes/conversacion/{$row_conversacion->id}/?{$busqueda_str}");
        ?>

        <a class="<?= $clase ?>" href="<?= $href ?>">
            <b><?= $texto ?></b>
            <br/>
            <span class=""><?= $this->App_model->nombre_usuario($row_conversacion->usuario_id, 2) ?></span>
        </a>
    <?php endforeach ?>
    
    <a class="list-group-item" href="<?= base_url('mensajes/explorar') ?>">
        <i class="fa fa-list-alt"></i> Explorar conversaciones
    </a>
</div>