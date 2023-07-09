<div id="temaInfoApp">
    <div class="center_box_750">
        <div class="">
            <a class="btn btn-link" href="<?= URL_ADMIN . "temas/copiar/{$row->id}" ?>">Clonar</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h2>{{ tema.nombre_tema }}</h2>
                    <p>{{ tema.descripcion }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3>Programas en los que está incluido ({{ programas.length }})</h3>
            </div>

            <table class="table bg-white">
                <thead>
                    <th>Núm.</th>
                    <th>Programa</th>
                    <th>Orden posición</th>
                </thead>
                <tbody>
                    <tr v-for="(programa, key) in programas">
                        <td width="10px" class="text-center">{{ key + 1 }}</td>
                        <td>
                            <a v-bind:href="`<?= base_url('programas/temas/') ?>` + programa.programa_id" class="">
                                {{ programa.nombre_programa }}
                            </a>
                        </td>
                        <td class="text-center">{{ parseInt(programa.orden) + 1 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>    
</div>

<script>
var temaInfoApp = new Vue({
    el: '#temaInfoApp',
    created: function(){
        //this.get_list()
    },
    data: {
        tema: <?= json_encode($row) ?>,
        programas: <?= json_encode($programas->result()) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>