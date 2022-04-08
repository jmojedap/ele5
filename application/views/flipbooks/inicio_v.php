<?php $this->load->view('assets/momentjs') ?>

<?php
    $flipbooks = array();
    foreach ($arr_flipbooks as $flipbook)
    {
        $flipbook['area_name'] = $areas[$flipbook['area_id']];
        $flipbook['nivel'] = $flipbook['nivel'];
        
        //Agregar cuestionarios
            $cuestionarios = array();
            foreach ($arr_cuestionarios as $cuestionario)
            {
                if ( $cuestionario['area_id'] == $flipbook['area_id'] )
                {
                    $cuestionarios[] = $cuestionario;
                }
            }
            $flipbook['cuestionarios'] = $cuestionarios;

        //Agregar elemento
        $flipbooks[] = $flipbook;
    }
?>

<style>
    .card_area{
        min-height: 400px;
    }

    .card_area .card-footer{
        background-color: #FFFFFF;
    }

    .card_area_header{
        padding-top: 30px;
        height: 100px;
    }

    .card_area_header{
        color: #FFFFFF;
    }

    .card_area_51 .card_area_header {
        background: rgb(132,64,168);
        background: -moz-linear-gradient(90deg, rgba(132,64,168,1) 0%, rgba(172,95,213,1) 100%);
        background: -webkit-linear-gradient(90deg, rgba(132,64,168,1) 0%, rgba(172,95,213,1) 100%);
        background: linear-gradient(90deg, rgba(132,64,168,1) 0%, rgba(172,95,213,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#8440a8",endColorstr="#ac5fd5",GradientType=1);
    }
    .card_area_50 .card_area_header {
        background: rgb(0,109,176);
        background: -moz-linear-gradient(90deg, rgba(0,109,176,1) 0%, rgba(23,155,236,1) 100%);
        background: -webkit-linear-gradient(90deg, rgba(0,109,176,1) 0%, rgba(23,155,236,1) 100%);
        background: linear-gradient(90deg, rgba(0,109,176,1) 0%, rgba(23,155,236,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#006db0",endColorstr="#179bec",GradientType=1);
    }
    .card_area_52 .card_area_header {
        background: rgb(46,184,191);
        background: -moz-linear-gradient(90deg, rgba(46,184,191,1) 0%, rgba(59,207,214,1) 100%);
        background: -webkit-linear-gradient(90deg, rgba(46,184,191,1) 0%, rgba(59,207,214,1) 100%);
        background: linear-gradient(90deg, rgba(46,184,191,1) 0%, rgba(59,207,214,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#2eb8bf",endColorstr="#3bcfd6",GradientType=1);
    }
    .card_area_53 .card_area_header {
        background: rgb(146,186,37);
        background: -moz-linear-gradient(90deg, rgba(146,186,37,1) 0%, rgba(178,222,59,1) 100%);
        background: -webkit-linear-gradient(90deg, rgba(146,186,37,1) 0%, rgba(178,222,59,1) 100%);
        background: linear-gradient(90deg, rgba(146,186,37,1) 0%, rgba(178,222,59,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#92ba25",endColorstr="#b2de3b",GradientType=1);
    }

    .label_level{
        display: inline-block;
        background-color: #F0F01D;
        color: #444;
        padding: 0 8px;
        border-radius: 3px 3px 3px 3px;
        -moz-border-radius: 3px 3px 3px 3px;
        -webkit-border-radius: 3px 3px 3px 3px;
        border: 0px solid #000000;
    }
</style>

<div id="inicio_app" class="container_narrow">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12" v-for="(flipbook, key) in flipbooks">
            <div class="card card_area" v-bind:class="`card_area_` + flipbook.area_id">
                <h3 class="card-header card_area_header">
                    <span class="label_level float-right">{{ flipbook.nivel }}</span>
                    {{ flipbook.area_name }}
                </h3>
                <div class="card-body">
                    <a v-bind:href="`<?= base_url("flipbooks/abrir/") ?>` + flipbook.id" class="btn btn-primary" target="_blank">
                        <i class="fa fa-book"></i>
                        Abrir
                    </a>
                    <a v-bind:href="`<?= base_url("usuarios/anotaciones/{$this->session->userdata('usuario_id')}/") ?>` + flipbook.id" class="btn btn-secondary" target="_blank">
                        Mis notas
                    </a>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-if="flipbook.cuestionarios.length > 0">
                        <b>Cuestionarios</b>
                        <i class="fa fa-caret-right"></i>
                    </li>
                    <li class="list-group-item" v-for="cuestionario in flipbook.cuestionarios">
                        <a v-bind:href="`<?= base_url("cuestionarios/preliminar/") ?>` + cuestionario.uc_id" target="_blank">
                            {{ cuestionario.nombre_cuestionario }}
                        </a>
                        <br>
                        <small class="text-muted">
                            (Termina {{ cuestionario.fecha_fin | ago }}) 
                        </small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });
    new Vue({
        el: '#inicio_app',
        data: {
            flipbooks: <?= json_encode($flipbooks) ?>,
            areas: <?= json_encode($areas); ?>
        },
        methods: {
            
        }
    });
</script>