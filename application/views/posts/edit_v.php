<?php $this->load->view('assets/summernote') ?>

<?php
    $arr_fields = array(
        'estado_id',
        'slug',
        'padre_id',
        'referente_1_id',
        'referente_2_id',
        'referente_3_id',
        'texto_1',
        'texto_2',
        'texto_3',
        'decimal_1',
        'decimal_2',
    );
?>

<script>
    var post_id = <?php echo $row->id ?>;

    $(document).ready(function(){
        $('#field-contenido').summernote({
            lang: 'es-ES',
            height: 300
        });

        $('#post_form').submit(function(){
            update_post();
            return false;
        });

// Funciones
//-----------------------------------------------------------------------------
    function update_post(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'posts/update/' + post_id,
            data: $('#post_form').serialize(),
            success: function(response){
                if ( response.status == 1 )
                {
                    toastr['success']('Guardado');
                }
            }
        });
    }
    });
</script>

<div id="edit_post" class="container">
    <form accept-charset="utf-8" method="POST" id="post_form">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="resumen">resumen</label>
                    <textarea name="resumen" id="field-resumen" rows="3" class="form-control"><?php echo $row->resumen ?></textarea>
                </div>


                <div class="form-group">
                    <label for="contenido" class="form-control-label">contenido</label>
                    <textarea name="contenido" id="field-contenido" class="form-control"><?php echo $row->contenido ?></textarea>
                </div>

                <div class="form-group">
                    <label for="contenido_json">contenido json</label>
                    <textarea name="contenido_json" id="field-contenido_json" rows="3" class="form-control"><?php echo $row->contenido_json ?></textarea>
                </div>


            </div>
            <div class="col-md-5">
                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success btn-block" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nombre_post" class="col-md-4 col-form-label text-right">Post Name</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            name="nombre_post"
                            required
                            class="form-control"
                            placeholder=""
                            title=""
                            value="<?php echo $row->nombre_post ?>"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tipo_id" class="col-md-4 col-form-label text-right">Tipo</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('tipo_id', $options_type, $row->tipo_id, 'class="form-control"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="publicado" class="col-md-4 col-form-label text-right">Fecha publicación</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-publicado"
                            name="publicado"
                            required
                            class="form-control"
                            placeholder="Fecha publicación"
                            title="Fecha publicación"
                            value="<?php echo $row->publicado ?>"
                            >
                    </div>
                </div>

                <?php foreach ( $arr_fields as $field ) { ?>
                    <div class="form-group row">
                        <label for="<?php echo $field ?>" class="col-md-4 col-form-label text-right"><?php echo str_replace('_',' ',$field) ?></label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                name="<?php echo $field ?>"
                                class="form-control"
                                title="<?php echo $field ?>"
                                value="<?php echo $row->$field ?>"
                                >
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </form>
</div>