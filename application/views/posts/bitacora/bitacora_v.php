<?php
    $modulo_ant = '';
?>

<style>
    .bitacora h1{
        color: #333;
    }

    .bitacora h2{
        color: #FFF;
        background-color: #4fc3f7;
        padding: 3px;
    }

    .bitacora h3{
        color: #89CB4E;
    }
</style>

<div class="card bitacora" style="max-width: 650px; margin: 0 auto;">
    <div class="card-body">
        <div class="text-center">
            <h1>INFORME DE ACTIVIDAD</h1>
        </div>
        <p>
            A continuación se hace un balance de las actividades, desarrolladas durante 
            el tercer trimestre de 2019, correspondientes a la ejecución del contrato para 
            el mantenimiento y actualización continua de la Plataforma Virtual En Línea Editores.
        </p>
        <?php foreach ( $bitacora->result() as $row_bitacora ) { ?>
            <?php if ( $row_bitacora->modulo != $modulo_ant ) { ?>
                <h2><?php echo $row_bitacora->modulo ?></h2>
            <?php } ?>
            <h3>
                <?php echo $row_bitacora->elemento ?>: <?php echo $row_bitacora->nombre_post ?>
            </h3>
            
            <p>
                <?php echo $this->Pcrn->fecha_formato($row_bitacora->fecha, 'Y-M-d') ?>
            </p>
            <?php echo $row_bitacora->contenido ?>
            <br>
            <a href="<?php echo base_url("posts/editar/{$row_bitacora->id}") ?>" class="btn btn-success btn-sm" target="_blank">Editar</a>
            <hr>
        <?php
            $modulo_ant = $row_bitacora->modulo;
        ?>
        <?php } ?>
    </div>
</div>