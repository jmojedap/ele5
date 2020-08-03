<?php $this->load->view('products/image/script_v') ?>

<?php
    $style_image_section = '';
    if ( $row->image_id == 0 ) { $style_image_section = 'display: none;';}
?>

<div class="card center_box_750" id="image_section" style="<?php echo $style_image_section ?>">
    <img
        id="product_image"
        class="card-img-top"
        width="100%"
        src="<?php echo URL_UPLOADS . $row->url_image ?>"
        alt="<?php echo $row->name ?>"
        onerror="this.src='<?php echo URL_IMG ?>app/pf_nd_2.png'"
    >
    <div class="card-body">
        

        <a class="btn btn-info" id="btn_crop" href="<?php echo base_url("products/cropping/{$row->id}") ?>">
            <i class="fa fa-crop"></i> Recortar
        </a>
        <button class="btn btn-warning" id="btn_remove_image">
            <i class="fa fa-trash"></i> Eliminar
        </button>
    </div>
</div>

<?php $this->load->view('products/image/form_v') ?>