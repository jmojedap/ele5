<?php
    $att_nombre_post = array(
        'id'     => 'nombre_post',
        'name'   => 'nombre_post',
        'class'  => 'form-control',
        'value'  => $row->nombre_post,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_contenido = array(
        'id'     => 'field-content',
        'name'   => 'contenido',
        'class'  => 'form-control',
        'value'  => $row->contenido,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_fecha = array(
        'id'     => 'fecha',
        'name'   => 'fecha',
        'class'  => 'form-control',
        'type'  =>  'date',
        'size' =>   9,
        'value'  => $this->Pcrn->fecha_formato($row->fecha, 'Y-m-d')
    );
    
    //Opciones
        $opciones_pago = $this->App_model->opciones_post('tipo_id = 91');
        $opciones_estado_pago = array(
            '' => '[ Estado de pago ]',
            '01' => 'Pagado',
            '02' => 'Finalizado',
            '03' => 'En desarrollo',
            '04' => 'Sin iniciar'
        );
    
    $campos_general = array(
        'texto_1' => 'Módulo',
        'texto_2' => 'Elemento',
        'referente_1_id' => 'Prioridad Orden',
        'decimal_1' => 'Horas Trabajo',
        'decimal_2' => 'Costo'
    );
?>

<?php $this->load->view('assets/summernote') ?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var post_id = '<?php echo $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#field-content').summernote({
            lang: 'es-ES',
            height: 300
        });

        $('#post_form').submit(function(){
            update_post();
            return false;
        });
    });

// FUNCIONES
//-----------------------------------------------------------------------------

    function update_post(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'posts/actualizar/' + post_id,
            data: $('#post_form').serialize(),
            success: function(response){
                if ( response.status == 1 )
                {
                    toastr['success']('Post actualizado');
                }
            }
        });
    }

</script>

<form accept-charset="utf-8" method="POST" id="post_form">

    <div class="row" style="max-width: 1300px; margin: 0 auto;">
        <div class="col-md-8">
            
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre_post">Título</label>
                        <?= form_input($att_nombre_post) ?>
                    </div>

                    <div class="form-group">
                        <?= form_textarea($att_contenido) ?>
                    </div>
                </div>
            </div>
            

        </div>

        <div class="col-md-4 form-horizontal">
            <div class="mb-2">
                <button class="btn btn-success btn-block" type="submit">
                    Guardar
                </button>
            </div>
            
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-4 control-label" for="referente_3_id">Contrato asociado</label>
                        <div class="col-md-8">
                            <?= form_dropdown('referente_3_id', $opciones_pago, $row->referente_3_id, 'class="form-control"') ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 control-label" for="fecha">Fecha ejecución</label>
                        <div class="col-md-8">
                            <?= form_input($att_fecha) ?>
                        </div>
                    </div>

                    <?php foreach ($campos_general as $nombre_campo => $titulo_campo) : ?>
                        <?php
                            $att_campo = array(
                                'id'     => $nombre_campo,
                                'name'   => $nombre_campo,
                                'class'  => 'form-control',
                                'value'  => $row->$nombre_campo,
                                'placeholder'   => $nombre_campo
                            );
                        ?>
                        <div class="form-group row">
                            <label class="col-md-4 control-label" for="<?= $nombre_campo ?>"><?= $titulo_campo ?></label>
                            <div class="col-md-8">
                                <?= form_input($att_campo) ?>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <div class="form-group row">
                        <label class="col-md-4 control-label" for="estado_id">Estado</label>
                        <div class="col-md-8">
                            <?= form_dropdown('estado_id', $opciones_estado_pago, '0'. $row->estado_id, 'class="form-control"') ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</form>