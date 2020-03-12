<?php
    //Establecer valores iniciales
    $nombre_cuestionario = 'Cuestionario ' . $this->session->userdata('username') . ' (' . date('d/M') . ')';

    $nivel = '';
    if ( $preguntas->result() > 0 ) { $nivel = '0' . $preguntas->row()->nivel; }

    $area_id = '';
    if ( $preguntas->result() > 0 ) { $area_id = '0' . $preguntas->row()->area_id; }

    //Estadísticas
    
?>

<style>
    .handle{
        cursor: move;
    }
</style>
<script src="<?php echo URL_RESOURCES ?>assets/sortablejs/Sortable.js"></script>

<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var str_preguntas = '<?php echo $str_preguntas ?>';

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#selector_form').submit(function(){
            //console.log('enviando');
            create_cuestionario();
            return false;
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function create_cuestionario(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'cuestionarios/selectorp_create',
            data: $('#selector_form').serialize(),
            success: function(response){
                console.log(response.cuestionario_id);
                if ( response.cuestionario_id > 0 ) {
                    window.location = base_url + 'cuestionarios/asignar/' + response.cuestionario_id
                }
            }
        });
    }

</script>

<a class="btn btn-secondary" href="<?php echo base_url('preguntas/explorar') ?>">
    <i class="fa fa-arrow-left"></i>
    Más preguntas
</a>

<div id="app_selectorp">
    <h3>Construyendo cuestionario</h3>
    <div class="row">
        <div class="col-md-4">
            <table class="table bg-white">
                <thead>
                    <th>Resumen</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>Preguntas seleccionadas</td>
                        <td>{{ list.length }}</td>
                    </tr>
                    <tr>
                        <td>Dificultad</td>
                        <td><?php echo number_format($avg_difficulty, 1) ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-plus"></i>
                    Generar cuestionario
                </div>
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="selector_form">
                        <div class="form-group row">
                            <label for="nombre_cuestionario" class="col-md-4 col-form-label text-right">Nombre</label>
                            <div class="col-md-8">
                                <input
                                    type="text"
                                    id="field-nombre_cuestionario"
                                    name="nombre_cuestionario"
                                    required
                                    autofocus
                                    class="form-control"
                                    placeholder="Nombre cuestionario"
                                    title="Nombre cuestionario"
                                    value="<?php echo $nombre_cuestionario ?>"
                                    >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nivel" class="col-md-4 col-form-label text-right">Nivel</label>
                            <div class="col-md-8">
                                <?php echo form_dropdown('nivel', $options_nivel, $nivel, 'class="form-control"') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area_id" class="col-md-4 col-form-label text-right">Área</label>
                            <div class="col-md-8">
                                <?php echo form_dropdown('area_id', $options_area, '051', 'class="form-control"') ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="offset-md-4 col-md-8">
                                <button class="btn btn-success w3">
                                    Crear
                                </button>
                            </div>
                        </div>
                        <hr>
                        <input type="text" class="form-control" name="str_preguntas" id="field-str_preguntas" value="<?php echo $str_preguntas ?>">

                    </form>
                </div>
            </div>

        </div>


        <div class="col-md-8">
            <div id="preguntas" class="sortable">
                <div class="card mb-1 mw750p" v-for="(pregunta, row_key) in list" v-bind:id="`pregunta_` + pregunta.id">
                    <div class="card-body">
                        <div class="float-right">
                            <button class="btn btn-light btn-sm" v-on:click="delete_element(row_key)">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="btn btn-light btn-sm handle">
                                <i class="fa fa-arrows-alt"></i>
                            </div>
                        </div>
                        <p v-html="pregunta.texto_pregunta"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#app_selectorp',
        data: {
            list: <?php echo json_encode($preguntas->result()) ?>,
            row_id: 0,
            row_key: 0
        },
        methods: {
            delete_element: function(row_key){
                this.row_key = row_key;
                this.row_id = this.list[this.row_key].id;
                console.log('eliminado' + this.row_id);

                axios.get(app_url + 'preguntas/selectorp_remove/' + this.row_id)
                .then(response => {
                    console.log(response.data.status)
                    this.list = response.data.preguntas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>

<!-- Sortable Preguntas Script -->
<script>
    var sortable_preguntas = document.getElementById('preguntas');
    
    new Sortable(sortable_preguntas, {
        handle: '.handle', // handle class
        animation: 200,
        ghostClass: 'bg-light',
        // Called when dragging element changes position
        onEnd: function(/**Event*/evt) {
            str_preguntas = '';
            $('#preguntas > div').each(function (index, element) {
                str_preguntas += element.id.replace('pregunta_', '') + ',';
            });
            str_preguntas = str_preguntas.substr(0, str_preguntas.length - 1);
            $('#field-str_preguntas').val(str_preguntas);
        }
    });
</script>

