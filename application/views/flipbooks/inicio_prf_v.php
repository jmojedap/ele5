<?php $this->load->view('assets/momentjs') ?>

<?php
    $niveles = [];
    $arr_areas = array();
    $areas_activas = array(50,51,52,53,464);
    foreach ($areas as $area)
    {
        if ( in_array($area['id'], $areas_activas) )
        {
            //Agregar contenidos y talleres
            $contenidos = array();
            $talleres = array();
            foreach ($arr_flipbooks as $flipbook)
            {
                if ( $flipbook['area_id'] == $area['id'] )
                {
                    if ( $flipbook['tipo_flipbook_id'] == 1 ) {
                        $talleres[] = $flipbook;
                    } else {
                        $contenidos[] = $flipbook;
                    }
                }
            }
            $area['contenidos'] = $contenidos;
            $area['talleres'] = $talleres;
    
            //Agregar elemento
            $arr_areas[] = $area;
        }
    }

    foreach ($arr_flipbooks as $flipbook)
    {
        //Agregar nivel
        if ( ! in_array($flipbook['nivel'], $niveles) ) {
            $niveles[] = $flipbook['nivel'];
        }
    }
?>

<link rel="stylesheet" href="<?= URL_RESOURCES ?>css/monster/inicio_cards_v1.css">

<div id="inicio_app" class="container_narrow">
    <div class="d-flex justify-content-center mb-2">
        <button class="btn btn-light mr-2">
            Nivel <i class="fa fa-caret-right"></i>
        </button>
        <button class="btn w100p mr-2" v-for="nivel in niveles"
            v-bind:class="{'btn-primary': nivel == currNivel, 'btn-outline-primary': nivel != currNivel }"
            v-on:click="setNivel(nivel)" v-bind:title="nivelName(nivel, 'name')"
            >
            {{ nivelName(nivel) }}
        </button>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12" v-for="(area, key) in areas">
            <div class="card card_area" v-bind:class="`card_area_` + area.id">
                <h3 class="card-header card_area_header">
                    {{ area.name }}
                </h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-if="area.contenidos.length > 0">
                        <b>Contenidos</b>
                        <i class="fa fa-caret-right"></i>
                    </li>
                    <div v-for="contenido in area.contenidos">
                        <li class="list-group-item" v-show="contenido.nivel == currNivel">
                            <span class="label_level">{{ nivelName(contenido.nivel) }}</span>
                            <a v-bind:href="`<?= base_url("flipbooks/abrir/") ?>` + contenido.flipbook_id" v-bind:title="contenido.nombre_flipbook" target="_blank">
                                Abrir
                            </a>
                        </li>
                        <li class="list-group-item" v-show="contenido.nivel == currNivel">
                            <span class="label_level">{{ nivelName(contenido.nivel) }}</span>
                            <a v-bind:href="`<?= base_url() . 'usuarios/anotaciones/' . $this->session->userdata('usuario_id') . '/' ?>` + contenido.flipbook_id" target="_blank">
                                Anotaciones
                            </a>
                        </li>
                    </div>
                    <!-- Recorrer talleres -->
                    <li class="list-group-item" v-for="taller in area.talleres" v-show="taller.nivel == currNivel">
                        <span class="label_level">{{ nivelName(taller.nivel) }}</span>
                        <a v-bind:href="`<?= base_url("flipbooks/abrir/") ?>` + taller.flipbook_id" target="_blank">
                            Actividades resueltas
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var areas = <?= json_encode($arr_areas); ?>;

// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
});

// VueApp
//-----------------------------------------------------------------------------
var inicio_app = new Vue({
    el: '#inicio_app',
    created: function(){
        this.startNivel()
    },
    data: {
        areas: areas,
        currNivel: null,
        niveles: <?= json_encode($niveles) ?>,
        arrNivel: <?= json_encode($arrNivel) ?>,
    },
    methods: {
        startNivel: function(){
            if ( this.niveles.length > 0 ) {
                this.currNivel = this.niveles[0]
            }
        },
        setNivel: function(value){
            this.currNivel = value
        },
        nivelName: function(value = '', field = 'abbreviation'){
            var nivelName = ''
            var item = this.arrNivel.find(row => row.cod == value)
            if ( item != undefined ) nivelName = item[field]
            return nivelName
        },
    }
});
</script>