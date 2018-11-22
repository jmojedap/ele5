<link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>plantillas/apanel2/mensajes.css">

<?php $this->load->view('assets/biggora_autocomplete');    //PlugIn, auto completar ?>
<?php $this->load->view('mensajes/conversacion/js_v'); ?>

<div class="row">
    <div class="col-md-4">
        <?php $this->load->view('mensajes/conversacion/lista_conversaciones_v'); ?>
    </div>
    
    <div class="col-md-8">
        
        <?php if ( $conversaciones->num_rows() > 0 ) { ?>
        
            <div class="panel panel-default" style="max-width: 800px;">
                <div class="panel-body">
                    <?php $this->load->view('mensajes/conversacion/encabezado_v'); ?>
                    <?php $this->load->view('mensajes/conversacion/mensajes_v'); ?>
                    <?php $this->load->view('mensajes/conversacion/form_mensaje_v'); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php $this->load->view('mensajes/conversacion/modal_eliminar'); ?>