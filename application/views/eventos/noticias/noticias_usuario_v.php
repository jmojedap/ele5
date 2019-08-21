<?php $this->load->view('eventos/noticias/noticias_js'); ?>

<link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel3/noticias.css">

<div class="row">
    <div class="col-md-3">
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_areas_v'); ?>
        </div>
        
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_tipos_v'); ?>
        </div>
        
        
    </div>
    <div class="col-md-9">
        
        <div id="listado_noticias">
            <?php $this->load->view('eventos/noticias/listado_noticias_v'); ?>
        </div>
        
        <div class="noticia text-center" style="display: none" id="no_mas_noticias">
            <i class="fa fa-info-circle"></i>
            No hay más noticias para mostrar
        </div>
        
        <button class="btn btn-secondary btn-block" id="mas_noticias" style="max-width: 600px;">
            Más
        </button>
    </div>
</div>


