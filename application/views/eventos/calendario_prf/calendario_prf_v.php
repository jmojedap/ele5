<?php $this->load->view('assets/fullcalendar4'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/clockpicker'); ?>

<?php    
    $get_print = $this->Pcrn->get_str(); //Get para link print 
?>

<style>
    .logo_mvl{
        display: inline;
        max-width: 135px;
    }

    .new_sesionv{
        border: 1px solid #DDD;
        cursor: pointer;
    }

    .new_sesionv:hover{
        border: 1px solid #AAA;
    }
</style>

<?php $this->load->view('eventos/calendario_prf/script_v') ?>

<div class="row">
    <div class="col-md-3">
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_grupos_v'); ?>
        </div>
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_areas_v'); ?>
        </div>
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_tipos_v'); ?>
        </div>
        
        <div class="mb-2">
            <a href="<?php echo base_url("eventos/imprimir_calendario/?{$get_print}") ?>" class="btn btn-info btn-block" id="boton_print" target="_blank">
                <i class="fa fa-print"></i> Imprimir
            </a>
        </div>

        <hr>
        <h4>Programar sesión virtual</h4>

        <img src="<?php echo URL_IMG ?>medios_videollamadas/zoom.png" alt="Logo zoom" class="rounded mb-2 new_sesionv" data-toggle="modal" data-target="#sesionv_modal" data-mvl="10" data-src="<?= URL_IMG ?>medios_videollamadas/zoom.png">
        <img src="<?php echo URL_IMG ?>medios_videollamadas/meet.png" alt="Logo meet" class="rounded mb-2 new_sesionv" data-toggle="modal" data-target="#sesionv_modal" data-mvl="20" data-src="<?= URL_IMG ?>medios_videollamadas/meet.png">
    </div>
    <div class="col-md-9">
        <div id='calendar'></div>
    </div>
</div>

<?php $this->load->view('eventos/calendario_prf/evento_modal_v') ?>
<?php $this->load->view('eventos/calendario_prf/sesionv_modal_v') ?>