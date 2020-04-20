<?php    
    //Datos adicionales elementos
        $this->load->helper('string');
        $id_alfanumerico = random_string('alnum', 16);
        
    //Imágenes elementos
        $carpeta_quices = base_url() . RUTA_UPLOADS . 'quices/';

    //Imagen de fondo
        $att_img = array(
            'id' => 'imagen_quiz',
            'src' => $imagen['src'],
            'width' => '100%',
            'style' => 'position: absolute; max-width: 800px'
        );
?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo URL_RESOURCES ?>js/Math.uuid.js"></script>
<link href="<?php echo URL_RESOURCES ?>css/animate.css" rel="stylesheet">

<?php $this->load->view('quices/construir/113/script_v') ?>
<?php $this->load->view('quices/construir/113/style_v') ?>

<div style="margin: 0px auto; width: 838px;">
    <div class="card mb-2">
        <div class="card-body">
            <?php if (strlen($imagen['src']) == 0) { ?>
                <?php $this->load->view('quices/construir/form_imagen_v') ?>
            <?php } else { ?>
                <a href="<?php echo base_url("quices/eliminar_archivo/{$quiz_id}/{$imagenes->row()->id_alfanumerico}") ?>"
                    class="btn btn-warning"
                    title="Eliminar imagen"
                    onclick="return confirm ('¿Confirma la eliminación de esta imagen?');"
                    >
                    <i class="fa fa-trash"></i>
                    Eliminar fondo
                </a>

                <div id="quiz_container" class="my-2">
                    <?= img($att_img) ?>

                    <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                        <?php
                            $style = "left: {$row_elemento->x}px; top: {$row_elemento->y}px; width: {$row_elemento->ancho}px; height: {$row_elemento->alto}px;";
                            $att_img_elemento['src'] = $carpeta_quices . $row_elemento->archivo;
                        ?>
                        <div id="draggable_<?= $row_elemento->id_alfanumerico ?>"
                            class="draggable animated flip"
                            style="<?= $style ?>"
                            data-id_alfanumerico="<?= $row_elemento->id_alfanumerico ?>"
                            >
                            <img src="<?php echo $att_img_elemento['src'] ?>" alt="Imagen elemento">

                        </div>
                    <?php endforeach ?>

                </div>

            <?php } ?>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-body">
            
            <form action="<?php echo base_url("quices/cargar_img_elemento_nuevo/{$row->id}") ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="id_alfanumerico" value="<?php echo $id_alfanumerico; ?>">
                <input type="hidden" name="quiz_id" value="<?php echo $row->id; ?>">
                <input type="hidden" name="tipo_id" value="1">
                <input type="hidden" name="orden" value="<?php echo $row->cant_elementos ?>">
                <input type="hidden" name="clave" value="<?php echo $row->cant_elemento ?>">

                <div class="row">
                    <div class="col-md-4">
                        Cargar imagen para ubicar <i class="fa fa-caret-right"></i>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="archivo" class="form_control-file" accept="image/x-png" required>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-block w4 float-right" type="submit">
                            Cargar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <table class="table bg-white mb-2">
        <thead>
            <th width="50px">Orden</th>
            <th>Elemento</th>
            <th width="35px"></th>
        </thead>

        <tbody>
            <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                <?php
                    $att_img_elemento['src'] = $carpeta_quices . $row_elemento->archivo;
                    $att_img_elemento['width'] = '40px';
                ?>
                <tr id="elemento_<?= $row_elemento->id_alfanumerico ?>">
                    <td><span class="etiqueta informacion w1"><?= ($row_elemento->orden + 1) ?></span></td>
                    <td><?= img($att_img_elemento) ?></td>
                    <td>
                        <span class="a4 eliminar_elemento" id="eliminar_<?= $row_elemento->id_alfanumerico ?>"><i class="fa fa-times"></i></span>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
