<?php $this->load->view('assets/summernote') ?>

<?php
    $arr_fields = array(
        'texto_2' => 'Sección',
        'texto_3' => 'Roles (Separados por guión -)'
    );

    $options_texto_1 = array(
        'General' => 'General',
        'Usuarios' => 'Usuarios',
        'Instituciones' => 'Instituciones',
        'Grupos' => 'Grupos',
        'Contenidos' => 'Contenidos',
        'Temas' => 'Temas',
        'Evidencias' => 'Evidencias',
        'Cuestionarios' => 'Cuestionarios',
        'Preguntas' => 'Preguntas',
        'Comercial' => 'Comercial',
        'Otro' => 'Otro'
    );
?>

<script>
    $(document).ready(function(){
        $('#field-contenido').summernote({
            lang: 'es-ES',
            height: 300
        });
    });
</script>

<div id="edit_post" class="container">
    <form accept-charset="utf-8" method="POST" id="post_form" @submit.prevent="send_form">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="nombre_post" class="">Título</label>
                    <input
                        name="nombre_post" placeholder="" title=""
                        type="text" required class="form-control"
                        value="<?php echo $row->nombre_post ?>"
                        >
                </div>

                <div class="form-group">
                    <label for="resumen">Resumen</label>
                    <textarea name="resumen" id="field-resumen" rows="3" class="form-control"><?php echo $row->resumen ?></textarea>
                </div>


                <div class="form-group">
                    <label for="contenido" class="form-control-label">Contenido</label>
                    <textarea name="contenido" id="field-contenido" class="form-control"><?php echo $row->contenido ?></textarea>
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
                    <label for="texto_1" class="col-md-4 col-form-label text-right">Módulo</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('texto_1', $options_texto_1, $row->texto_1, 'class="form-control" required') ?>
                    </div>
                </div>

                <?php foreach ( $arr_fields as $field => $field_title ) { ?>
                    <div class="form-group row">
                        <label for="<?php echo $field ?>" class="col-md-4 col-form-label text-right"><?= $field_title ?></label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                name="<?php echo $field ?>"
                                class="form-control"
                                title="<?php echo $field_title ?>"
                                value="<?php echo $row->$field ?>"
                                >
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </form>
</div>

<script>
    new Vue({
        el: '#edit_post',
        created: function(){
            //this.get_list();
        },
        data: {
            post_id: <?= $row->id ?>
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'posts/update/' + this.post_id, $('#post_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['success']('Guardado');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>