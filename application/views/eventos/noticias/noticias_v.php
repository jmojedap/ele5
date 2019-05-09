<?php $this->load->view('usuarios/biblioteca_menu_v'); ?>
<?php $this->load->view('eventos/noticias/noticias_js'); ?>

<link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel2/noticias.css">

<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="sep1">
            <?php $this->load->view('eventos/filtro_areas_v'); ?>
        </div>
        
        <div class="sep2">
            <?php $this->load->view('eventos/filtro_tipos_v'); ?>
        </div>
        
        
    </div>
    <div class="col-md-9 col-lg-9">
        
        <?php if ( $this->session->userdata('srol') != 'interno' ){ ?>
            <?php $this->load->view('eventos/noticias/form_publicacion_v'); ?>
        <?php } ?>        
        
        <div id="listado_noticias">
            <?php $this->load->view('eventos/noticias/listado_noticias_v'); ?>
        </div>
        
        <div class="noticia text-center" style="display: none" id="no_mas_noticias">
            <i class="fa fa-info-circle"></i>
            No hay más noticias para mostrar
        </div>
        
        <div style="max-width: 600px;">
            <div class="btn btn-default btn-block" id="mas_noticias">
                Más
            </div>
        </div>
    </div>
</div>


