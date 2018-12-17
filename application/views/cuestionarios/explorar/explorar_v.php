<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>

<script src="<?php echo URL_RECURSOS . 'js/pcrn.js' ?>"></script>

<?php
    $elemento_s = 'cuestionario';  //Elemento en singular
    $elemento_p = 'cuestionarios'; //Elemento en plural
        
    //Clases botones acción
        $clases_btn['eliminar_seleccionados'] = 'd-none';
        if ( $this->session->userdata('rol_id') <= 1 ) { $clases_btn['eliminar_seleccionados'] = ''; }
        if ( $filtro_alcance == 'mis_cuestionarios' ) { $clases_btn['eliminar_seleccionados'] = ''; }
        
        $clases_btn['exportar'] = 'd-none';
        if ( $this->session->userdata('rol_id') <= 2 ) { $clases_btn['exportar'] = ''; }
?>

<?php $this->load->view('cuestionarios/explorar/script_js'); ?>
<?php $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-6">
        <?php $this->load->view('cuestionarios/explorar/form_busqueda_v'); ?>
    </div>

    <div class="col-md-3">
        <a class="btn btn-warning text-light <?php echo $clases_btn['eliminar_seleccionados'] ?>"
            title="Eliminar los <?php echo $elemento_p ?> seleccionados"
            data-toggle="modal"
            data-target="#modal_eliminar"
            >
            <i class="fa fa-trash"></i>
        </a>
        
        <div class="btn-group hidden-xs <?php echo $clases_btn['exportar'] ?>" role="group">
            <?php echo anchor("cuestionarios/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="pull-right sep1">
            <?php $this->load->view('comunes/bs4/paginacion_ajax_v'); ?>
        </div>
    </div>
</div>

<div id="tabla_resultados">
    <?php $this->load->view("{$carpeta_vistas}tabla_v"); ?>
</div>

<?php $this->load->view('comunes/bs4/modal_eliminar_v'); ?>