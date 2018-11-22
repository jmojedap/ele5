<?php $this->load->view('head_includes/chosen_jquery'); ?>

<?php
    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    
    //Opciones, tabla
        $opciones_tabla = $this->Pcrn->query_to_array($tablas, 'nombre_tabla', 'nombre_tabla');
    
    //Opciones, parte a explorar
        $reg_desde = 1;
        $reg_hasta = $max_registros;
        $cant_partes = ceil($num_registros/$max_registros);

        for ($index = 0; $index < $cant_partes; $index++) {
            $num_parte = $index + 1;
            $opciones_parte[$index] = "Parte {$num_parte} ({$reg_desde} a {$reg_hasta})";
            
            //Para siguiente ciclo
            $reg_desde += $reg_hasta;
            $reg_hasta += $max_registros;
        }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    
    var base_url = '<?= base_url() ?>';
    var nombre_tabla = '<?= $nombre_tabla ?>';
    var destino_pre = '<?= $destino_pre ?>';
    var max_registros = '<?= $max_registros ?>';
    
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#campo-tabla').change(function(){
            nombre_tabla = $(this).val();
            window.location = base_url + 'develop/msexcel/' + nombre_tabla;
        });
        
        $('#campo-parte').change(function(){
            var offset = max_registros * $(this).val();
            var href_boton = base_url + destino_pre + offset;
            //alert(href_boton);
            $('#link_exportar').attr('href', href_boton);
        });
        
    });
</script>

<?php if ( ! is_null($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= form_open($destino_form, $att_form) ?>
            <div class="form-group">
                <label for="tabla" class="col-sm-3 control-label">Tabla</label>
                <div class="col-sm-9">
                    <?= form_dropdown('tabla', $opciones_tabla, $nombre_tabla, 'id="campo-tabla" class="form-control chosen-select" required title="Seleccione la tabla"'); ?>
                </div>
            </div>
        
            <div class="form-group">
                <label for="parte" class="col-sm-3 control-label">Parte a exportar (<?= $cant_partes ?>)</label>
                <div class="col-sm-9">
                    <?= form_dropdown('parte', $opciones_parte, NULL, 'id="campo-parte" class="form-control chosen-select" required title="Seleccione la parte a exportar"'); ?>
                </div>
            </div>
        
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <?= anchor("{$destino_pre}0", 'Exportar', 'id="link_exportar" class="btn btn-primary" title=""') ?>
                </div>
            </div>
        <?= form_close('') ?>
    </div>
</div>