<?php
    $att_img = array(
        'src' => URL_UPLOADS . 'enunciados/' . $row->texto_2
    );
?>

<div class="center_box_750">
    <div class="card card-default">
        <div class="card-header">
            <?= $row->nombre_post ?>
        </div>
        <div class="card-body">
            <?= $row->contenido ?>
        </div>
    </div>
    <?php if ( strlen($row->texto_2) > 0 ) { ?>
        <div class="thumbnail">

                <?= img($att_img) ?>
        </div>
    <?php } ?>
</div>