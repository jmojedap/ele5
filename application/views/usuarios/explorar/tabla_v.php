<style>
    .explore_head{
        border-bottom: 1px solid #f9f9f9;
        padding: 5px 0px;
        margin: 1px;
        background-color: #e3f2fd;
    }

    .explore_row{
        border-bottom: 1px solid #f9f9f9;
        padding: 5px 0px;
        margin: 1px;
    }

    .explore_row:hover{
        background-color: #f9f9f9;
        border-bottom: 1px solid #f1f1f1;
    }
</style>

<div class="card" style="padding: 0px;">
    <div class="card-body_no">
        <div class="row explore_head">
            <div class="col-md-1 col-xs-6" style="max-width: 30px;">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="check_todos" name="check_todos">
                    <label class="custom-control-label" for="check_todos">
                        <span class="text-hide">-</span>
                    </label>
                </div>
            </div>
            <div class="col">
                
            </div>
        </div>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
        <?php
            //Variables
                $nombre_elemento = character_limiter($row_resultado->apellidos. ' ' . $row_resultado->nombre, 70);
                $link_elemento = anchor("{$controlador}/actividad/{$row_resultado->id}", $nombre_elemento);
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
                
                <div class="col-md-1 col-xs-6">
                    <?php echo $row_resultado->id ?>
                </div>
                
                <div class="col">
                    <?php echo $link_elemento ?>
                    <br>
                    <span class="text-muted"><?php echo $row_resultado->username ?></span>
                </div>

                <div class="col">
                    <span class="text-muted">
                        <i class="fa fa-university"></i>
                    </span>
                    <?= $this->App_model->nombre_institucion($row_resultado->institucion_id) ?>
                </div>

                <div class="col">
                    <button class="btn btn-success btn-sm">
                        Activo
                    </button>
                    <button class="btn btn-warning btn-sm">
                        Pagado No
                    </button>
                    <button class="btn btn-secondary btn-sm" title="Restaurar contraseña del usuario">
                        <i class="fa fa-sync-alt"></i>
                        Contraseña
                    </button>
                </div>
            </div>

        <?php } //foreach ?>

    </div>
</div>
