<div id="api_data_app">
    <h1>{{ data.paginas.length }}</h1>
    <div>
        <div v-for="(pagina, pk) in data.paginas">
            <span>{{ pagina.archivo_imagen }}</span>
            <img
                v-bind:src="`https://www.plataformaenlinea.com/v3/assets/uploads/pf_zoom/` + pagina.archivo_imagen"
                class="w120p mr-2 mb-2"
                alt="imagen"
            >
        </div>
    </div>
</div>

<script>
var api_data_app = new Vue({
    el: '#api_data_app',
    created: function(){
        this.cargar_data();
    },
    data: {
        app_url: '<?php echo base_url() ?>',
        flipbook_id: <?= $row->id ?>,
        data: {
            relacionados: {
                1: {},
                2: {},
                3: {}
            }
        },
        loading: false,
    },
    methods: {
        cargar_data: function () {
            axios.get(this.app_url + 'flipbooks/data/' + this.flipbook_id)
            .then(response => {
                this.data = response.data;
            })
            .catch(function (error) { console.log(error) })
        },
    }
})
</script>