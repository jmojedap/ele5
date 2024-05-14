<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>

<?php 

    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    $att_fecha = array(
        'id' => 'campo-fecha',
        'name' => 'fecha',
        'value' => date('Y') . '-12-31',
        'class' => 'form-control bs_datepicker',
        'required' => TRUE
    );

    $opciones_institucion = $this->App_model->opciones_institucion('id > 0', 'Elija una institución');
?>

<script>
// Variables
//-----------------------------------------------------------------------------

    var base_url = '<?= base_url() ?>';
    var post_id = <?= $row->id ?>;
    var controlador = 'contenidos_ap';
    var elemento_id = 0;
    var meta_id = 0;
    
// Document Ready
//-----------------------------------------------------------------------------
    
    $(document).ready(function ()
    {
        $('#formulario').submit(function () {
            ap_guardar_asignacion();
            return false;
        });
        
        $('.eliminar_meta').click(function(){
            meta_id = $(this).data('meta_id');
        });
        
        $('#eliminar_elemento').click(function(){
            ap_eliminar_asignacion();
        });
        
    });
    
// Funciones
//-----------------------------------------------------------------------------
    
    //Ajax
    function ap_guardar_asignacion()
    {
        $.ajax({
            type: 'POST',
            url: base_url + controlador + '/guardar_asignacion',
            data: $('#formulario').serialize(),
            success: function (resultado) {
                if ( resultado.ejecutado === 1 )
                {
                    window.location = base_url + controlador + '/instituciones/' + post_id;
                }
            }
        });
    }
    
    //Ajax
    function ap_eliminar_asignacion()
    {
        //console.log(meta_id);
        
        $.ajax({
            type: 'POST',
            url: base_url + controlador + '/eliminar_asignacion/' + post_id + '/' + meta_id,
            success: function (resultado) {
                if ( resultado.ejecutado === 1 )
                {
                    window.location = base_url + controlador + '/instituciones/' + post_id;
                }
            }
        });
    }
</script>

<div class="center_box_920">
    <table class="table bg-white">
        <thead>
            <th class="<?= $clases_col['nombre_institucion'] ?>">Instituciones asignadas</th>
            <th>Fecha máx</th>
            <th></th>
        </thead>
    
        <tbody>
            
            <form id="formulario" action="<?php echo base_url($destino_form) ?>">
                <?php echo form_hidden('post_id', $row->id) ?>
                <tr class="info">
                    <td>
                        <?php echo form_dropdown('institucion_id', $opciones_institucion, NULL, 'class="form-control chosen-select"') ?>
                    </td>
                    <td>
                        <?= form_input($att_fecha); ?>
                    </td>
                    <td>
                        <button class="btn btn-info" type="submit">
                            Agregar
                        </button>
                    </td>
                </tr>
            </form>
            <?php foreach ($instituciones->result() as $row_institucion) : ?>
                <tr>
                    <td class="<?= $clases_col['nombre_institucion'] ?>">
                        <a href="<?php echo base_url("instituciones/flipbooks/{$row_institucion->institucion_id}") ?>">
                            <?php echo $row_institucion->nombre_institucion ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $this->Pcrn->fecha_formato($row_institucion->fecha_1); ?>
                    </td>
                    <td>
                        <button class="btn btn-default btn-xs eliminar_meta" data-toggle="modal" data-target="#modal_eliminar" data-meta_id = <?php echo $row_institucion->meta_id ?>>
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php $this->load->view('comunes/modal_eliminar_simple'); ?>
</div>
