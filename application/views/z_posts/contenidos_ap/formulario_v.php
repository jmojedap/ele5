<?php

    $editando = FALSE;
    if ( $this->uri->segment(2) == 'editar' )
    {
        $editando = TRUE;
    } else {
        $valores_form['nivel'] = '095';
    }

    //Formulario    
        $att_form = array(
            'class' =>  'form-horizontal'
        );

        $att_nombre_post = array(
            'id'     => 'campo-nombre_post',
            'name'   => 'nombre_post',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores_form['nombre_post']
        );
        
        $att_resumen = array(
            'id'     => 'campo-resumen',
            'name'   => 'resumen',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores_form['resumen'],
            'rows' => 3
        );
        
        //Opciones
            $opciones_tipo_ap = $this->Item_model->opciones('categoria_id = 153');
            $opciones_area = $this->Item_model->opciones_id('categoria_id = 1');
            $opciones_nivel = $this->App_model->opciones_nivel('item');


        $att_submit = array(
            'class'  => 'btn btn-success w120p',
            'value'  => 'Guardar'
        );
    
?>

<script>
// Variables
//-----------------------------------------------------------------------------

    var base_url = '<?= base_url() ?>';
    var elemento_id = '<?= $row->id ?>';
    var controlador = 'contenidos_ap';
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#formulario').submit(function(){
            ap_crud();
            return false;
        });
        
    });
    
// Funciones
//-----------------------------------------------------------------------------
    
    //Ajax
    function ap_crud()
    {
     $.ajax({
       type: 'POST',
           url: base_url + controlador + '/crud/insertar',
            data: $('#formulario').serialize(),
            success: function(resultado){
                if ( resultado.nuevo_id > 0){
                    window.location = base_url + 'contenidos_ap/editar/' + resultado.nuevo_id;
                }
            }
     });
    }
</script>

<?php if ( ! is_null($menu_view) ) { ?>
    <?php $this->load->view($menu_view); ?>
<?php } ?>

<div class="card center_box_750">
    <div class="card-body">
        <form id="formulario" class="form-horizontal">
            <?php echo form_hidden('tipo_id', 4311) ?>
            <div class="form-group row">
                <label for="nombre_post" class="col-sm-4 col-form-label text-right">Nombre *</label>
                <div class="col-sm-8">
                    <?= form_input($att_nombre_post) ?>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="resumen" class="col-sm-4 col-form-label text-right">Descripción *</label>
                <div class="col-sm-8">
                    <?= form_textarea($att_resumen) ?>
                </div>
            </div>
        
            <div class="form-group row">
                <label for="tipo_id" class="col-sm-4 col-form-label text-right">Tipo *</label>
                <div class="col-sm-8">
                    <?= form_dropdown('referente_3_id', $opciones_tipo_ap, $valores_form['referente_3_id'], 'id="campo-nivel" class="form-control" required title="Seleccione el tipo de contenido AP"'); ?>
                </div>
            </div>
        
            <div class="form-group row">
                <label for="referente_2_id" class="col-sm-4 col-form-label text-right">Niveles</label>
                <div class="col-sm-8">
                    <input
                        name="texto_2" type="text" class="form-control"
                        required
                        title="Niveles"
                        v-model="form_values.texto_2"
                    >
                    <small id="emailHelp" class="form-text text-muted">Códigos de nivel o grado, separadas por coma. Ej. 1,2,3.</small>
                </div>
            </div>

            <div class="form-group row">
                <label for="referente_2_id" class="col-sm-4 col-form-label text-right">Área *</label>
                <div class="col-sm-8">
                    <?= form_dropdown('referente_2_id', $opciones_area, $valores_form['referente_2_id'], 'id="campo-area" class="form-control" required title="Seleccione el área del contenido AP"'); ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-md-4 col-sm-8">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>