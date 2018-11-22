<?php
    $att_img = array(
        'src' => URL_UPLOADS . 'enunciados/' . $row->texto_2
    );
?>

<div class="row">
    <div class="col col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $row->nombre_post ?>
            </div>
            <div class="panel-body">
                <?= $row->contenido ?>
            </div>
        </div>
    </div>
    <div class="col col-sm-6">
        <?php if ( strlen($row->texto_2) > 0 ) { ?>
            <div class="thumbnail">

                    <?= img($att_img) ?>
            </div>
        <?php } ?>
    </div>
</div>