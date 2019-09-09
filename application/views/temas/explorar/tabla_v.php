<style>
    .explore_head{
        border-bottom: 1px solid #f9f9f9;
        padding: 5px 0px;
        margin: 1px;
        background-color: #e3f2fd;
    }

    .explore_row{
        border-bottom: 1px solid #e9e9e9;
        padding: 5px 0px;
        margin: 1px;
    }

    .explore_row:hover{
        background-color: #f9f9f9;
        border-bottom: 1px solid #f1f1f1;
    }

    .explore_col_id { width: 80px; }
    .explore_col_check {
        max-width: 300px;
        background-color: red;
    }
</style>

<div class="card" style="padding: 0px;">
    <div>
        <div class="row explore_head">
            <div class="col-md-1 col-xs-6" style="max-width: 30px;">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="check_todos" name="check_todos">
                    <label class="custom-control-label" for="check_todos">
                        <span class="text-hide">-</span>
                    </label>
                </div>
            </div>
            <div class="col-md-1">
                ID
            </div>
            <div class="col-md-1">Cód.</div>
            <div class="col-md-3">Tema</div>
            <div class="col-md-2">Tipo</div>
            <div class="col-md-3">Nivel | Área</div>
            <div class="col-md-1">Evidencias</div>
        </div>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
        <?php
            //Variables
                $nombre_elemento = character_limiter($row_resultado->nombre_tema, 70);
                $link_elemento = anchor("{$controlador}/index/{$row_resultado->id}", $nombre_elemento);

            //Otros datos
                $cant_quices = $this->Tema_model->quices($row_resultado->id)->num_rows();
                $clase_cant = 'badge badge-secondary';
                if ( $cant_quices > 0 ) { $clase_cant = 'badge badge-success'; }
                
                $clase_tipo = '';
                if ( $row_resultado->tipo_id ) { $clase_tipo = 'info'; }
        ?>
            <div class="row explore_row" id="fila_<?php echo $row_resultado->id ?>">

                <div class="col-md-1 col-xs-6" style="max-width: 30px;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input check_registro" data-id="<?php echo $row_resultado->id ?>" id="check_<?php echo $row_resultado->id ?>">
                        <label class="custom-control-label" for="check_<?php echo $row_resultado->id ?>">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </div>
                
                <div class="col-md-1 col-xs-6" style="max-width: 80px;">
                    <?php echo $row_resultado->id ?>
                </div>
                
                <div class="col-md-1 col-sm-12">
                    <?php echo $row_resultado->cod_tema; ?>
                </div>

                <div class="col-md-4 col-sm-12">
                    <?php echo $link_elemento ?>
                </div>

                <div class="col-md-2 col-sm-12">
                    <?php echo $arr_tipos[$row_resultado->tipo_id] ?>
                </div>

                <div class="col-md-3 col-xs-12">
                    <span class="etiqueta nivel w2"><?php echo $arr_nivel[$row_resultado->nivel] ?></span>
                    <?php echo $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                </div>

                <div class="col-md-1 col-xs-12">
                    <span class="<?= $clase_cant ?>"><?= $cant_quices ?></span>
                </div>
            </div>

        <?php } //foreach ?>

    </div>
</div>
