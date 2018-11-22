<?php
    $i = 0;
?>

<?php $this->load->view('grupos/submenu_flipbooks_v') ?>

<div class="section group">
    <div class="col col_box span_1_of_4">
        <div class="info_container_body">
            <h3>Flipbooks</h3>
            <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                <?= anchor("flipbooks/ver_flipbook/{$row_flipbook->flipbook_id}", $this->App_model->nombre_flipbook($row_flipbook->flipbook_id), 'class="a3"') ?>
            <?php endforeach ?>
            <hr/>
            
            <h3>Temas</h3>
        </div>
    </div>
</div>