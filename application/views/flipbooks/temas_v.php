<?php $this->load->view('assets/vue') ?>
<?php $this->load->view('assets/toastr') ?>
<?php $this->load->view('assets/biggora_autocomplete'); ?>

<?php
    $arr_tipos = $this->Item_model->arr_item(11);   //El índice tiene cero inicial, ej 1 => 01
?>

<script>
// Variables
//-----------------------------------------------------------------------------    
    var base_url = '<?php echo base_url() ?>';
    var flipbook_id = <?php echo $flipbook_id ?>;
    var tema_id = 0;
    
// Document Ready
//-----------------------------------------------------------------------------
    
    $(document).ready(function ()
    {
        $('#importar_temas').click(function(){
            importar_temas();
        });

        $('#campo_q').typeahead({
            ajax: {
                url: '<?= base_url() ?>app/arr_elementos_ajax/tema',
                method: 'post',
                triggerLength: 2
            },
            onSelect: agregar_tema
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function importar_temas(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'flipbooks/importar_temas_programa/' + flipbook_id,
            success: function(response){
                console.log(response.mensaje);
                if ( response.ejecutado == 1 ) {
                    window.location = base_url + 'flipbooks/temas/' + flipbook_id;
                }
            }
        });
    }

    function agregar_tema(item) 
    {
        tema_id = item.value;
        console.log('Agregando tema: ' + tema_id);
    }
</script>

<div class="sep1">
    <button class="btn btn-success" id="importar_temas" title="Importar temas de programas">
        Importar <?php echo $areas->num_rows(); ?>
    </button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">
        Agregar
    </button>
</div>

<div id="app_temas">
    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="">Tema</label>
                      <input
                        type="text"
                        id="campo_q"
                        name="q"
                        class="form-control"
                        placeholder="Agregar tema"
                        title="Agregar tema"
                        >
                      </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="add_tema" data-dismiss="modal">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-hover bg-blanco">
        <thead>
            <th width="45px">ID</th>
            <th width="100px">Cód. tema</th>
            <th>Nombre tema</th>
            <th>Nivel</th>
            <th>Área</th>
            <th>Tipo tema</th>
            <th width="35px"></th>
        </thead>
        <tbody>
            <tr v-for="(tema, key) in lista">
                <td class="warning">{{ tema.id }}</td>
                <td>{{ tema.cod_tema }}</td>
                <td>
                    <a v-bind:href="'<?php echo base_url('temas/preguntas/') ?>' + tema.id">
                        {{ tema.nombre_tema }}
                    </a>
                </td>
                <td>{{ tema.nivel }}</td>
                <td>{{ areas[tema.area_id] }}</td>
                <td>{{ tema.tipo_id }} - {{ tema.orden }}</td>
                <td>
                    <button class="btn btn-sm" v-on:click="eliminar_tema(key)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#app_temas',
        created: function(){
            this.get_list();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            flipbook_id: '<?php echo $flipbook_id ?>',
            lista: [],
            areas: {
            <?php foreach ( $areas->result() as $row_area ) { ?>
            <?php echo $row_area->id ?>: '<?php echo $row_area->item ?>',
            <?php } ?>
            }
        },
        methods: {
            get_list: function(){
                axios.get(this.app_url + 'flipbooks/lista_temas/' + this.flipbook_id)
                .then(response => {
                    this.lista = response.data.lista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            add_tema: function(){
                axios.get(this.app_url + 'flipbooks/agregar_tema/' + flipbook_id + '/' + tema_id + '/1')
                .then(response => {
                    console.log(response.data.mensaje);
                    if ( response.data.ejecutado == 1 ) { this.get_list(); }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminar_tema: function(key){
                var tema = this.lista[key];
                console.log('eliminando: ' + tema.ft_id);
                
                var params = new FormData();
                params.append('ft_id', tema.ft_id);
                
                axios.post(this.app_url + 'flipbooks/crud_temas/' + flipbook_id + '/eliminar', params)
                .then(response => {
                    if ( response.data.ejecutado == 1 ) {
                        toastr["warning"]('Registro eliminado');
                    }
                    
                    console.log(response.data.mensaje);
                })
                .catch(function (error) {
                    console.log(error);
                });

            }
        }
    });
</script>