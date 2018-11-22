<?php
    $att_img = array(
        'src' => URL_UPLOADS . 'enunciados/' . $row->archivo_imagen
    );
?>

<div class="section group">
    <div class="col col_box span_2_of_4">
        <div class="info_container_body">
            <p>
                <?= $row->texto_enunciado ?>
            </p>
        </div>
    </div>
    
    <div class="col col_box span_2_of_4">
        <div class="info_container_body">
            <?= img($att_img) ?>
        </div>
    </div>
</div>