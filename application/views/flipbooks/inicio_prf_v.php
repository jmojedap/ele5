<?php $this->load->view('assets/momentjs') ?>

<?php
    $arr_areas = array();
    $areas_activas = array(50,51,52,53);
    foreach ($areas as $area_id => $area_name)
    {
        if ( in_array($area_id, $areas_activas) )
        {
            $area['id'] = $area_id;
            $area['name'] = $area_name;

            //Agregar contenidos y talleres
            $contenidos = array();
            $talleres = array();
            foreach ($arr_flipbooks as $flipbook)
            {
                if ( $flipbook['area_id'] == $area_id )
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

            $grupos = array();
            foreach ($arr_grupos as $grupo_id) {
                //$grupo = $this->Db_model->row_id('grupo', $grupo_id);
                $grupo = $grupo_id;
                $grupos[] = $grupo;
            }

            $area['grupos'] = $grupos;
    
            //Agregar elemento
            $arr_areas[] = $area;
        }
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
                        <li class="list-group-item" >
                            <span class="label_level">{{ contenido.nivel }}</span>
                            <a v-bind:href="`<?php echo base_url("flipbooks/abrir/") ?>` + contenido.flipbook_id" v-bind:title="contenido.nombre_flipbook" target="_blank">
                                Abrir
                            </a>
                        </li>
                        <li class="list-group-item" v-for="grupo_id in area.grupos">
                            <span class="label_level">{{ contenido.nivel }}</span>
                            <a v-bind:href="`<?= base_url() . 'usuarios/anotaciones/' . $this->session->userdata('usuario_id') . '/' ?>` + contenido.flipbook_id" target="_blank">
                                Anotaciones
                            </a>
                        </li>
                    </div>
                    <!-- Recorrer talleres -->
                    <li class="list-group-item" v-for="taller in area.talleres">
                        <span class="label_level">{{ taller.nivel }}</span>
                        <a v-bind:href="`<?php echo base_url("flipbooks/abrir/") ?>` + taller.flipbook_id" target="_blank">
                            Actividades resueltas
                        </a>
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
            areas: <?php echo json_encode($arr_areas); ?>
        },
        methods: {
            
        }
    });
</script>